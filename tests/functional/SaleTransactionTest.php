<?php

use Petflow\Litle\Transaction\SaleTransaction as SaleTransaction;

class SaleTransactionTest extends FunctionalTestCase {

	/**
	 * @dataProvider transactionProvider
	 */
	public function testSaleTransaction($source, $expected_response) {
		$saleTransaction = new SaleTransaction();

		$response = $saleTransaction->make($source);

		foreach ($expected_response as $clause) {

			$this->assertArrayHasKey($clause[0], $response);

			// so we only want to check for specific values when the 4th array element
			// in the given clause is true AND the environment is for certification.
			if (!isset($clause[3]) || ($clause[3] && static::$is_certification_environment)) {
				$this->{'assert'.$clause[1]}($clause[2], $response[$clause[0]]);
			} 
		}
	}

	/**
	 * Data Provided for Sales Transaction
	 * @return [type] [description]
	 */
	public static function transactionProvider() {
		return [
			// Referencing Authorization Test Data in Litle Reference Guide 8.17
			// Table 2-1 on page 58
			//
			// ----------------------------------------------------------------
			// orderId = 1
			// ----------------------------------------------------------------
			[	
				// Source
				[
					'amount' 		=> 10100,
					'orderId' 		=> '1',
					'orderSource'	=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'John Smith',
						'addressLine1' 	=> '1 Main St.',
						'city' 			=> 'Burlington',
						'state' 		=> 'MA',
						'zip' 			=> '01803-3747',
						'country' 		=> 'US'
					],
					'card' => [
						'number' 				=> '4457010000000009',
						'expDate' 				=> '0114',
						'cardValidationNum'		=> '349',
						'type' 					=> 'VI'
					]
				],
				// Response
				[
					['response',  	'Equals', 	'000'],
					['message',	  	'Equals', 	'Approved'],
					['auth_code',	'Equals',	'11111', 	true],
					['avs_result',	'Equals',	'01', 		true],
					['cv_result',	'Equals',	'M', 		true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 2
			// ----------------------------------------------------------------
			[	
				// Source
				[
					'amount' 		=> 20200,
					'orderId' 		=> '1',
					'orderSource'	=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'Mike J Hammer',
						'addressLine1' 	=> '2 Main St.',
						'addressLine2'	=> 'Apt. 222',
						'city' 			=> 'Riverside',
						'state' 		=> 'RI',
						'zip' 			=> '02915',
						'country' 		=> 'US'
					],
					'card' => [
						'number' 				=> '5112010000000003',
						'expDate' 				=> '0214',
						'cardValidationNum'		=> '261',
						'type' 					=> 'MC',
						'authenticationValue'	=> 'BwABBJQ1AgAAAAAgJDUCAAAAAAA='
					]
				],
				// Response
				[
					['response',  	'Equals', 	'000'],
					['message',	  	'Equals', 	'Approved'],
					['auth_code',	'Equals',	'22222', 	true],
					['avs_result',	'Equals',	'10', 		true],
					['cv_result',	'Equals',	'M', 		true],
					['auth_result',	'Equals',	'Note: Not returned for MasterCard', true]
				]
			]
		];
	}
	
}