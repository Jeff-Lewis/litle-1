<?php

use Petflow\Litle\Transaction\SaleTransaction as SaleTransaction;

class SaleTransactionTest extends FunctionalTestCase {

	/**
	 * @dataProvider transactionProvider
	 */
	public function testSaleTransaction($source, $expected_response) {
		$response = (new SaleTransaction('PETFLOW', '', '120400', static::getCfg()))->make($source);

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
			//
			// Referencing Authorization Test Data in Litle Reference Guide 8.17
			// Table 2-1 on page 58
			// 
			// Each array index of the top-level array holds two arrays, the first
			// for the source information and the second for the expected response
			// information.
			// 
			// The expected response array is formatted as:
			// 
			// 	[response key, assertion, value, certification_model]
			// 	
			// When the certification_mode is set to true then the test will check
			// for the given assertion. Otherwise it will just be checking for
			// array key existence in the response.
			//
			// ----------------------------------------------------------------
			// orderId = 1
			// ----------------------------------------------------------------
			'Approved VISA Transaction' => [	
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
			'Approved MasterCard Transaction' => [	
				[
					'amount' 		=> 20200,
					'orderId' 		=> '2',
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
				[
					['response',  	'Equals', 	'000'],
					['message',	  	'Equals', 	'Approved'],
					['auth_code',	'Equals',	'22222', 	true],
					['avs_result',	'Equals',	'10', 		true],
					['cv_result',	'Equals',	'M', 		true],
					['auth_result',	'Equals',	'Note: Not returned for MasterCard', true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 3 
			// ----------------------------------------------------------------
			'Approved Discover Transaction' => [	
				[
					'amount' 		=> 30300,
					'orderId' 		=> '3',
					'orderSource'	=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'Elieen Jones',
						'addressLine1' 	=> '3 Main St.',
						'city' 			=> 'Bloomfield',
						'state' 		=> 'CT',
						'zip' 			=> '06002',
						'country' 		=> 'US'
					],
					'card' => [
						'number' 				=> '6011010000000003',
						'expDate' 				=> '0314',
						'cardValidationNum'		=> '758',
						'type' 					=> 'DI'
					]
				],
				[
					['response',  	'Equals', 	'000'],
					['message',	  	'Equals', 	'Approved'],
					['auth_code',	'Equals',	'33333', 	true],
					['avs_result',	'Equals',	'12', 		true],
					['cv_result',	'Equals',	'M', 		true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 4
			// ----------------------------------------------------------------
			'Approved American Express Transaction' => [	
				[
					'amount' 		=> 40400,
					'orderId' 		=> '4',
					'orderSource'	=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'Bob Black',
						'addressLine1' 	=> '4 Main St.',
						'city' 			=> 'Laurel',
						'state' 		=> 'MD',
						'zip' 			=> '20708',
						'country' 		=> 'US'
					],
					'card' => [
						'number' 				=> '375001000000005',
						'expDate' 				=> '0414',
						'type' 					=> 'AX'
					]
				],
				[
					['response',  	'Equals', 	'000'],
					['message',	  	'Equals', 	'Approved'],
					['auth_code',	'Equals',	'44444', 	true],
					['avs_result',	'Equals',	'10', 		true],
					['cv_result',	'Equals',	'',			true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 5
			// ----------------------------------------------------------------
			'Approved Visa w/ Auth Transaction' => [	
				[
					'amount' 				=> 50500,
					'orderId' 				=> '5',
					'orderSource'			=> 'ecommerce',
					'card'					=> [
						'type'					=> 'VI',
						'number'				=> '4457010200000007',
						'expDate'				=> '0514',
						'cardValidationNum' 	=> '463',
						'authenticationValue' 	=> 'BwABBJQ1AgAAAAAgJDUCAAAAAAA='
					]
				],
				[
					['response',  	'Equals', 	'000'],
					['message',	  	'Equals', 	'Approved'],
					['auth_code',	'Equals',	'55555', 	true],
					['avs_result',	'Equals',	'32', 		true],
					['cv_result',	'Equals',	'M',		true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 6
			// ----------------------------------------------------------------
			'Insufficient Funds' => [	
				[
					'amount' 				=> 60600,
					'orderId' 				=> '6',
					'orderSource'			=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'Joe Green',
						'addressLine1' 	=> '6 Main St.',
						'city' 			=> 'Derry',
						'state' 		=> 'NH',
						'zip' 			=> '03038',
						'country' 		=> 'US'
					],
					'card'					=> [
						'type'					=> 'VI',
						'number'				=> '4457010100000008',
						'expDate'				=> '0614',
						'cardValidationNum' 	=> '992'
					]
				],
				[
					['response',  	'Equals', 	'110',					true],
					['message',	  	'Equals', 	'Insufficient Funds',	true],
					['auth_code',	'Equals',	'', 					true],
					['avs_result',	'Equals',	'34', 					true],
					['cv_result',	'Equals',	'P',					true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 7
			// ----------------------------------------------------------------
			'Invalid Account Number' => [	
				[
					'amount' 				=> 70700,
					'orderId' 				=> '7',
					'orderSource'			=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'Jane Murray',
						'addressLine1' 	=> '7 Main St.',
						'city' 			=> 'Amesberry',
						'state' 		=> 'MA',
						'zip' 			=> '01913',
						'country' 		=> 'US'
					],
					'card'					=> [
						'type'					=> 'MC',
						'number'				=> '5112010100000002',
						'expDate'				=> '0714',
						'cardValidationNum' 	=> '251'
					]
				],
				[
					['response',  	'Equals', 	'301',						true],
					['message',	  	'Equals', 	'Invalid Account Number', 	true],
					['auth_code',	'Equals',	'', 						true],
					['avs_result',	'Equals',	'34', 						true],
					['cv_result',	'Equals',	'N',						true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 8 
			// ----------------------------------------------------------------
			'Call Discover' => [	
				[
					'amount' 				=> 80800,
					'orderId' 				=> '8',
					'orderSource'			=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'Mark Johnson',
						'addressLine1' 	=> '8 Main St.',
						'city' 			=> 'Manchester',
						'state' 		=> 'NH',
						'zip' 			=> '03013',
						'country' 		=> 'US'
					],
					'card'					=> [
						'type'					=> 'DI',
						'number'				=> '6011010100000002',
						'expDate'				=> '0814',
						'cardValidationNum' 	=> '184'
					]
				],
				[
					['response',  	'Equals', 	'123',				true],
					['message',	  	'Equals', 	'Call Discover',	true],
					['auth_code',	'Equals',	'', 				true],
					['avs_result',	'Equals',	'34', 				true],
					['cv_result',	'Equals',	'P',				true]
				]
			],
			// ----------------------------------------------------------------
			// orederId = 9
			// ----------------------------------------------------------------
			'Pick Up Card' => [	
				[
					'amount' 				=> 90900,
					'orderId' 				=> '9',
					'orderSource'			=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'James Miller',
						'addressLine1' 	=> '9 Main St.',
						'city' 			=> 'Boston',
						'state' 		=> 'MA',
						'zip' 			=> '02134',
						'country' 		=> 'US'
					],
					'card'					=> [
						'type'					=> 'AX',
						'number'				=> '375001010000003',
						'expDate'				=> '0914',
						'cardValidationNum' 	=> '0421'
					]
				],
				[
					['response',  	'Equals', 	'303',			true],
					['message',	  	'Equals', 	'Pick Up Card', true],
					['auth_code',	'Equals',	'', 			true],
					['avs_result',	'Equals',	'34', 			true],
					['cv_result',	'Equals',	'',				true]
				]
			]						
		];
	}
	
}