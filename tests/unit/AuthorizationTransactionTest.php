<?php

use Petflow\Litle\Transaction\Request\AuthorizationRequest;

/**
 * Authorization Transaction Test
 */
class AuthorizationTransactionTest extends UnitTestCase {

	/**
	 * Make a Successful Auth Transaction
	 */
	public function testMakeApprovedAuthTransaction() {
		$transaction = static::transactions()['01-approved'];
		$litle 		 = static::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('9-Digit zip and address match', $response->getAVS()['description']);
		$this->assertTrue($response->isApproved());
		$this->assertTrue($response->isAVSApproved());
	}

	/**
	 * Make AVS Failure Transaction
	 */
	public function testMakeApprovedAuthTransactionAVSFailure() {
		$transaction = static::transactions()['02-avs_failure'];
		$litle 		 = static::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('9-Digit zip matches, address does not match', $response->getAVS()['description']);
		$this->assertTrue($response->isApproved());		
		$this->assertFalse($response->isAVSApproved());
	}

	/**
	 * Make Failed Failure Transaction
	 */
	public function testMakeFailedAuthTransaction() {
		$transaction = static::transactions()['03-failed'];
		$litle 		 = static::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest([], [], $litle))->make($transaction['request']);

		$this->assertFalse($response->isApproved());		
	}

	/**
	 * Missing Order id failed
	 *
	 * @expectedException Petflow\Litle\Exception\MissingRequestParameterException
	 */
	public function testFailedAuthTransactionMissingOrderId() {
		$transaction = static::transactions()['04-missing-order-id'];
		$litle 		 = static::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest([], [], $litle))->make($transaction['request']);
	}

	/**
	 * Missing Card Number failed
	 *
	 * @expectedException Petflow\Litle\Exception\MissingRequestParameterException
	 */
	public function testFailedAuthTransactionMissingCardNumber() {
		$transaction = static::transactions()['05-missing-card-number'];
		$litle 		 = static::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest([], [], $litle))->make($transaction['request']);
	}

	/**
	 * Missing Zip code failed
	 *
	 * @expectedException Petflow\Litle\Exception\MissingRequestParameterException
	 */
	public function testFailedAuthTransactionMissingZipCode() {
		$transaction = static::transactions()['06-missing-address-zip'];
		$litle 		 = static::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest([], [], $litle))->make($transaction['request']);
	}

	/**
	 * Auth Transaction Data
	 */
	private static function transactions() {
		return [
			'01-approved' => [
				'request'  => [
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
				'response' => static::makeAuthorizationXMLResponse(
					[],
					[
						'response' => '000',
						'message' => 'Approved',
						'authCode' => '11111',
						'avsResult' => '01',
						'cardValidationResult' => 'M'
					]
				)
			],
			'02-avs_failure' => [
				'request'  => [
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
				'response' => static::makeAuthorizationXMLResponse(
					[],
					[
						'response' => '000',
						'message' => 'Approved',
						'authCode' => '22222',
						'avsResult' => '11',
						'cardValidationResult' => 'M'
					]
				)
			],
			'03-failed' => [
				'request'  => [
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
				'response' => static::makeAuthorizationXMLResponse(
					[],
					[
						'response' => '301',
						'message' => 'Invalid Account Number',
						'authCode' => '000000',
						'avsResult' => '10',
						'cardValidationResult' => 'M'
					]
				)
			],
			'04-missing-order-id' => [
				'request' => [
					'card' => [
						'expDate' 				=> '0114',
						'cardValidationNum'		=> '349',
						'type' 					=> 'VI'
					],
					'billToAddress' => []
				],
				'response' => []
			],
			'05-missing-card-number' => [
				'request' => [
					'orderId' => 235212,
					'card' => [
						'expDate' 				=> '0114',
						'cardValidationNum'		=> '349',
						'type' 					=> 'VI'
					],
					'billToAddress' => []
				],
				'response' => []
			],
			'06-missing-address-zip' => [
				'request' => [
					'orderId' => 235212,
					'card' => [
						'number' 				=> '4457010000000009',
						'expDate' 				=> '0114',
						'cardValidationNum'		=> '349',
						'type' 					=> 'VI'
					],
					'billToAddress' => []
				],
				'response' => []
			]
		];
	}

}