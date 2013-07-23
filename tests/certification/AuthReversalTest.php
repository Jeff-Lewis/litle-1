<?php

use Petflow\Litle\Transaction\Request\AuthorizationRequest;
use Petflow\Litle\Transaction\Request\CaptureRequest;
use Petflow\Litle\Transaction\Request\AuthorizationReversalRequest;

/**
 * Authorization Reversal Certifications
 */
class ReversalTests extends CertificationTestCase {

	/**
	 * Test one for Connection
	 */
	public function testConnection() {
		$transaction = static::reversalTransactions()['32'];

		$source 	  = $transaction[0];
		$expectations = $transaction[1];

		$response = (new AuthorizationRequest(static::getParams()))->make($source);

		$this->assertEquals($expectations['response'], $response->getCode());
		$this->assertEquals($expectations['message'], $response->getDetails()['message']);
		$this->assertEquals($expectations['auth_code'], $response->getAuthCode());
		$this->assertEquals($expectations['avs_result'], $response->getAvs()['code']);

		// when available, do capture
		$capture = static::captureTransactions()[$source['orderId']];

		if (!is_null($capture)) {
			$capture_response = (new CaptureRequest(static::getParams()))->make([
				'orderId' 	 => $source['orderId'],
				'litleTxnId' => $response->getLitleTxnId(),
				'amount'     => $capture[0]['amount']
			]);

			$this->assertEquals($capture[1]['response'], $capture_response->getCode());
			$this->assertEquals($capture[1]['message'], $capture_response->getDetails()['message']);
		}

		// perform reversal
		$reversal_transaction = static::reversalTransactions()[$source['orderId']];

		$reversal_response = (new AuthorizationReversalRequest(static::getParams()))->make([
			'litleTxnId' 	=> $response->getLitleTxnId()
		]);

		$this->assertEquals($reversal_transaction[1]['response'], $reversal_response->getCode());
		$this->assertEquals($reversal_transaction[1]['message'], $reversal_response->getDetails()['message']);
	}

	/**
	 * @dataProvider authTransactions
	 */
	public function testReversals($source, $expectations) {
		$response = (new AuthorizationRequest(static::getParams()))->make($source);

		$this->assertEquals($expectations['response'], $response->getCode());
		$this->assertEquals($expectations['message'], $response->getDetails()['message']);
		$this->assertEquals($expectations['auth_code'], $response->getAuthCode());
		$this->assertEquals($expectations['avs_result'], $response->getAvs()['code']);

		// when available, do capture
		$capture = static::captureTransactions()[$source['orderId']];

		if (!is_null($capture)) {
			$capture_response = (new CaptureRequest(static::getParams()))->make([
				'orderId' 	 => $source['orderId'],
				'litleTxnId' => $response->getLitleTxnId(),
				'amount'     => $capture[0]['amount']
			]);

			$this->assertEquals($capture[1]['response'], $capture_response->getCode());
			$this->assertEquals($capture[1]['message'], $capture_response->getDetails()['message']);
		}

		// perform reversal
		$reversal_transaction = static::reversalTransactions()[$source['orderId']];

		$reversal_response = (new AuthorizationReversalRequest(static::getParams()))->make([
			'litleTxnId' 	=> $response->getLitleTxnId(),
		]);

		$this->assertEquals($reversal_transaction[1]['response'], $reversal_response->getCode());
		$this->assertEquals($reversal_transaction[1]['message'], $reversal_response->getDetails()['message']);
	}

	public static function authTransactions() {
		return [
			'32' => [	
				[
					'amount' 		=> 10100,
					'orderId' 		=> '32',
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
			'33' => [
				[
					'amount' 		=> 20200,
					'orderId' 		=> '33',
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
			'34' => [	
				[
					'amount' 		=> 30300,
					'orderId' 		=> '34',
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
					'avs_result' 	=>	'10',
					'cv_result'  	=>	'M'
				]
			],
			'35' => [
				[
					'amount' 		=> 40400,
					'orderId' 		=> '35',
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
					'avs_result'  	=>	'12',
					'cv_result' 	=>	''
				]
			]
		];
	}

	public static function captureTransactions() {
		return [
			'32' => [
				[
					'amount' => 5005
				],
				[
					'response' => '000',
					'message' => 'Approved'
				]
			],
			'33' => null,
			'34' => null,
			// '35' => [
			// 	[
			// 		'amount' => 20020
			// 	],
			// 	[
			// 		'response' => '000',
			// 		'message' => 'Approved'
			// 	]
			// ]
			// '36' => null
		];
	}

	public static function reversalTransactions() {
		return [
			'32' => [
				'response' => '111',
				'message' => 'Authorization amount has already been depleted.'
			],
			'33' => [
				'response' => '000',
				'message' => 'Approved'
			],
			'34' => [
				'response' => '000',
				'message' => 'Approved'
			],
			// '35' => [
			// 	[
			// 		'amount' => 20020
			// 	],
			// 	[
			// 		'response' => '000',
			// 		'message' => 'Approved'
			// 	]
			// ],
			// '36' => [
			// 	[
			// 		'amount' => 10000
			// 	]
			// ]
		];
	}
}