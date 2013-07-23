<?php

use Petflow\Litle\Transaction\Request\AuthorizationRequest;
use Petflow\Litle\Transaction\Request\CaptureRequest;

/**
 * Authorization and Capture Testing
 */
class AuthCaptureTest extends CertificationTestCase {

	/**
	 * Test Connection using first Transaction
	 */
	public function testConnection() {
		$auth = static::authTransactions()['1'];

		$source = $auth[0];
		$expectations = $auth[1];

		$response = (new AuthorizationRequest(static::getParams()))->make($source);

		$this->assertEquals($response->getCode(), $expectations['response']);
		$this->assertEquals($response->getDetails()['message'], $expectations['message']);
		$this->assertEquals($response->getAuthCode(), $expectations['auth_code']);
		$this->assertEquals($response->getAvs()['code'], $expectations['avs_result']);


		// for orders 1 thru 5 we do capture!
		if ($source['orderId'] >= 1 && $source['orderId'] <= 5)  {
			$capture_response = (new CaptureRequest(static::getParams()))->make([
				'orderId' 	 => $source['orderId'],
				'litleTxnId' => $response->getLitleTxnId()
			]);

			$this->assertEquals($capture_response->getCode(), static::captureTransactions()[$source['orderId']]['response']);
			$this->assertEquals($capture_response->getDetails()['message'], static::captureTransactions()[$source['orderId']]['message']);
		}
	}
	
	/**
	 * @dataProvider authTransactions
	 */
	public function testAuthCapture($source, $expectations) {
		$response = (new AuthorizationRequest(static::getParams()))->make($source);

		$this->assertEquals($response->getCode(), $expectations['response']);
		$this->assertEquals($response->getDetails()['message'], $expectations['message']);
		$this->assertEquals($response->getAuthCode(), $expectations['auth_code']);
		$this->assertEquals($response->getAvs()['code'], $expectations['avs_result']);

		// for orders 1 thru 5 we do capture!
		if ($source['orderId'] >= 1 && $source['orderId'] <= 5)  {
			$capture_response = (new CaptureRequest(static::getParams()))->make([
				'orderId' 	 => $source['orderId'],
				'litleTxnId' => $response->getLitleTxnId()
			]);

			$this->assertEquals($capture_response->getCode(), static::captureTransactions()[$source['orderId']]['response']);
			$this->assertEquals($capture_response->getDetails()['message'], static::captureTransactions()[$source['orderId']]['message']);
		}
	}


	/**
	 * For Authorization
	 */
	public static function authTransactions() {
		return [
			//
			// Referencing Authorization Test Data in Litle Reference Guide 8.17
			// Table 2-1 on page 58
			// 
			// Each array index of the top-level array holds two arrays, the first
			// for the source information and the second for the expected response
			// information.
			//
			// ----------------------------------------------------------------
			// orderId = 1
			// ----------------------------------------------------------------
			'1' => [	
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
					'response'   => '000',
					'message'    => 'Approved',
					'auth_code'  => '11111',
					'avs_result' => '01',
					'cv_result'  => 'M'
				]
			],
			// ----------------------------------------------------------------
			// orederId = 2
			// ----------------------------------------------------------------
			'2' => [	
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
					'response'   	=> 	'000',
					'message'   	=> 	'Approved',
					'auth_code'  	=>	'22222',
					'avs_result'  	=>	'10',
					'cv_result' 	=>	'M',
					'auth_result'   =>	'Note: Not returned for MasterCard'
				]
			],
			// ----------------------------------------------------------------
			// orederId = 3 
			// ----------------------------------------------------------------
			'3' => [	
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
					'response'   	=> 	'000',
					'message'	   	=> 	'Approved',
					'auth_code'  	=>	'33333',
					'avs_result' 	=>	'12',
					'cv_result'  	=>	'M'
				]
			],
			// ----------------------------------------------------------------
			// orederId = 4
			// ----------------------------------------------------------------
			'4' => [	
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
					'response'   	=> 	'000',
					'message'	   	=> 	'Approved',
					'auth_code'  	=>	'44444',
					'avs_result'  	=>	'10',
					'cv_result' 	=>	''
				]
			],
			// ----------------------------------------------------------------
			// orederId = 5
			// ----------------------------------------------------------------
			'5' => [	
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
					],
					'requireAddress' => false
				],
				[
					'response'   	=> 	'000',
					'message'	   	=> 	'Approved',
					'auth_code'  	=>	'55555',
					'avs_result'  	=>	'32',
					'cv_result'  	=>	'M'
				]
			],
			// ----------------------------------------------------------------
			// orederId = 6
			// ----------------------------------------------------------------
			'6' => [	
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
					'response'   	=> 	'110',
					'message'	   	=> 	'Insufficient Funds',
					'auth_code' 	=>	'',
					'avs_result'  	=>	'34',
					'cv_result' 	=>	'P'
				]
			],
			// ----------------------------------------------------------------
			// orederId = 7
			// ----------------------------------------------------------------
			'7' => [	
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
					'response'   	=> 	'301',
					'message'	   	=> 	'Invalid Account Number',
					'auth_code' 	=>	'',
					'avs_result'  	=>	'34',
					'cv_result'  	=>	'N'
				]
			],
			// ----------------------------------------------------------------
			// orederId = 8 
			// ----------------------------------------------------------------
			'8' => [
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
					'response'  	=> 	'123',
					'message'	   	=> 	'Call Discover',
					'auth_code'  	=>	'',
					'avs_result'  	=>	'34',
					'cv_result'  	=>	'P'
				]
			],
			// ----------------------------------------------------------------
			// orederId = 9
			// ----------------------------------------------------------------
			'9' => [
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
					'response'   	=> 	'303',
					'message'	   	=> 	'Pick Up Card',
					'auth_code'  	=>	'',
					'avs_result'  	=>	'34',
					'cv_result'  	=>	''
				]
			]						
		];
	}

	/**
	 * For Capture
	 */
	public static function captureTransactions() {
		return [	
			'1' => [
				'response' => '000',
				'message' => 'Approved'
			],
			'2' => [
				'response' => '000',
				'message' => 'Approved'
			],
			'3' => [
				'response' => '000',
				'message' => 'Approved'
			],
			'4' => [
				'response' => '000',
				'message' => 'Approved'
			],
			'5' => [
				'response' => '000',
				'message' => 'Approved'
			]
		];
	}
}