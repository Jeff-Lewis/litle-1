<?php

use Petflow\Litle\Transaction\Request\AuthorizationReversalRequest;
use Petflow\Litle\Exception;
use Petflow\Litle\Utility\TestHelper;

/**
 * Testing Auth Reversal Transactions
 */
class AuthorizationReversalTransactionTest extends UnitTestCase {

	/**
	 * Successful auth reversal
	 */
	public function testSuccessfulAuthReversal() {
		$transaction = static::transactions()['01-approved'];
		$litle       = TestHelper::mockLitleRequest('authReversalRequest', $transaction['response']);

		$result = (new AuthorizationReversalRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('472743', $result->getLitleTxnId());
		$this->assertTrue($result->isApproved());
	}

	/**
	 * Failed auth reversal due to missing txn id
	 *
	 * @expectedException Petflow\Litle\Exception\MissingRequestParameterException
	 */
	public function testFailedAuthReversalMissingTxnId() {
		$transaction = static::transactions()['02-missing-txnid'];
		$litle       = TestHelper::mockLitleRequest('authReversalRequest', $transaction['response']);

		$result = (new AuthorizationReversalRequest([], [], $litle))->make($transaction['request']);
	}

	/**
	 * Transactions Used in this Test
	 */
	private static function transactions() {
		return [
			'01-approved' => [
				'request' => [
					'litleTxnId' => 472743
				],
				'response' => TestHelper::makeAuthReversalXMLResponse([], [
					'litleTxnId' => '472743',
					'response' => '000',
					'message' => 'Approved'
				])
 			],
 			'02-missing-txnid' => [
 				'request' => [
 					'orderId' => 12415
 				],
 				'response' => []
 			]
		];
	}
}