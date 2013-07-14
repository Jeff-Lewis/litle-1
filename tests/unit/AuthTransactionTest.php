<?php

use Petflow\Litle\Transaction\AuthorizationTransaction as AuthTransaction;

/**
 * Authorization Transaction Test
 */
class AuthTransactionTest extends UnitTestCase {

	/**
	 * Make a Successful Auth Transaction
	 */
	public function testMakeSuccessfulAuthTransaction() {
		$transaction = $this->authTransactions()['01-approved'];
		$litle = Mockery::mock('LitleOnlineRequest')
			->shouldReceive('authorizationRequest')
			->andReturn($transaction['response'])
			->getMock();

		$response = (new AuthTransaction([], [], $litle))->make($transaction['request']);

		$this->assertEquals('9-Digit zip and address match', $response['avs_response']['description']);
		$this->assertEquals('Match', $response['cv_response']['description']);
		$this->assertEquals('approved', $response['transaction_response']['type']);
	}

	/**
	 * Auth Transaction Data
	 */
	public function authTransactions() {
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
				'response' => $this->makeAuthorizationXMLResponse(
					[],
					[
						'response' => '000',
						'message' => 'Approved',
						'authCode' => '11111',
						'avsResult' => '01',
						'cardValidationResult' => 'M'
					]
				)
			]
		];
	}

}