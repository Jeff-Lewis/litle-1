<?php

use Petflow\Litle\Transaction\Request\AuthorizationRequest;
use Petflow\Litle\Utility\TestHelper;

/**
 * Authorization Transaction Test
 */
class AuthorizationTransactionTest extends UnitTestCase {

	/**
	 * Make a Successful Auth Transaction
	 */
	public function testMakeApprovedAuthTransaction() {
		$transaction = static::transactions()['01-approved'];
		$litle 		 = TestHelper::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest(['mode' => 'production'], [], $litle))->make($transaction['request']);

		$this->assertEquals('1', $response->getOrderId());
		$this->assertEquals('Y', $response->getAVS()['actual_code']);
		$this->assertTrue($response->isApproved());
		$this->assertTrue($response->isAVSApproved());
	}

	/**
	 * Make AVS Failure Transaction
	 */
	public function testMakeApprovedAuthTransactionAVSFailure() {
		$transaction = static::transactions()['02-avs_failure'];
		$litle 		 = TestHelper::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest(['mode' => 'production'], [], $litle))->make($transaction['request']);

		$this->assertEquals('2', $response->getOrderId());
		$this->assertEquals('N', $response->getAVS()['actual_code']);
		$this->assertTrue($response->isApproved());		
		$this->assertFalse($response->isAVSApproved());
	}

	/**
	 * Make Failed Failure Transaction
	 */
	public function testMakeFailedAuthTransaction() {
		$transaction = static::transactions()['03-failed'];
		$litle 		 = TestHelper::mockLitleRequest('authorizationRequest', $transaction['response']);

		$response = (new AuthorizationRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('3', $response->getOrderId());
		$this->assertFalse($response->isApproved());		
	}

	/**
	 * @expectedException MissingRequestParameterException
	 * @dataProvider missingParameterTransactions
	 */
	public function testFailedAuthTransactionMissingParameter($request, $response) {
		$litle = TestHelper::mockLitleRequest('authorizationRequest', $request);
		$response = (new AuthorizationRequest([], [], $litle))->make($response);
	}

	/**
	 * Missing PArameter Transactions
	 */
	public static function missingParameterTransactions() {
		return array_slice(static::transactions(), 3);
	}

	/**
	 * Auth Transaction Data
	 */
	private static function transactions() {
		return [
			'01-approved' => [
				'request'  => [
					'amount' 		=> 101.00,
					'id' 			=> '1',
					'orderId'       => '1',
					'billToAddress' => [
						'name' 			=> 'John Smith',
						'addressLine1' 	=> '1 Main St.',
						'city' 			=> 'Burlington',
						'state' 		=> 'MA',
						'zip' 			=> '01803-3747',
						'country' 		=> 'US'
					],
					'token' => [
						'litleToken' 			=> '1111000101039449',
						'expDate' 				=> '0114'
					]
				],
				'response' => TestHelper::makeAuthorizationXMLResponse(
					[
						'id' => 1
					],
					[
						'response' => '000',
						'message' => 'Approved',
						'authCode' => '11111',
						'fraudResult' => array(
							'avsResult' => '02'
						),
						'cardValidationResult' => 'M'
					]
				)
			],
			'02-avs_failure' => [
				'request'  => [
					'amount' 		=> '101.00',
					'id' 			=> '2',
					'orderId'		=> '2',
					'billToAddress' => [
						'name' 			=> 'John Smith',
						'addressLine1' 	=> '1 Main St.',
						'city' 			=> 'Burlington',
						'state' 		=> 'MA',
						'zip' 			=> '01803-3747',
						'country' 		=> 'US'
					],
					'token' => [
						'litleToken' 			=> '1111000101039449',
						'expDate' 				=> '0114'
					]
				],
				'response' => TestHelper::makeAuthorizationXMLResponse(
					[
						'id' => '2'
					],
					[
						'response' => '000',
						'message' => 'Approved',
						'authCode' => '22222',
						'fraudResult' => array(
							'avsResult' => '20'
						),
						'cardValidationResult' => 'M'
					]
				)
			],
			'03-failed' => [
				'request'  => [
					'amount' 		=> '10100',
					'id' 			=> '3',
					'orderId' 		=> '3',
					'orderSource'	=> 'ecommerce',
					'billToAddress' => [
						'name' 			=> 'John Smith',
						'addressLine1' 	=> '1 Main St.',
						'city' 			=> 'Burlington',
						'state' 		=> 'MA',
						'zip' 			=> '01803-3747',
						'country' 		=> 'US'
					],
					'token' => [
						'litleToken' 			=> '1111000101039449',
						'expDate' 				=> '0114'
					]
				],
				'response' => TestHelper::makeAuthorizationXMLResponse(
					[
						'id' => '3'
					],
					[
						'response' => '301',
						'message' => 'Invalid Account Number',
						'authCode' => '000000',
						'fraudResult' => array(
							'avsResult' => '10'
						),
						'cardValidationResult' => 'M'
					]
				)
			],
			'04-missing-order-id' => [
				'request' => [
					'amount' => 10100,
					'token' => [
						'litleToken' 			=> '1111000101039449',
						'expDate' 				=> '0114'
					],
					'billToAddress' => []
				],
				'response' => []
			],
			'05-missing-card-number' => [
				'request' => [
					'id'   => '6',
					'amount' => 10050,
					'token' => [
						'expDate' 				=> '0114'
					],
					'billToAddress' => []
				],
				'response' => []
			],
			'06-missing-address-zip' => [
				'request' => [
					'id'   => '6',
					'amount' => 10050,
					'token' => [
						'litleToken' 			=> '1111000101039449',
						'expDate' 				=> '0114'
					],
					'billToAddress' => []
				],
				'response' => []
			]
		];
	}

}