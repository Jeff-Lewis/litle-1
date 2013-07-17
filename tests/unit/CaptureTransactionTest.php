<?php

use Petflow\Litle\Transaction\Request\CaptureRequest;
use Petflow\Litle\Utility\TestHelper;

/**
 * Test Capture Transaction
 */
class CaptureTransactionTest extends UnitTestCase {
	
	/**
	 * Approved Capture Transaction
	 */
	public function testApprovedCaptureTransaction () {
		$transaction = static::transactions()['01-approved'];
		$litle       = TestHelper::mockLitleRequest('captureTransaction', $transaction['response']);

		$result = (new CaptureRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('323462', $result->getLitleTxnId());
		$this->assertTrue($result->isApproved());
	}

	/**
	 * Failed Capture Transaction
	 */
	public function testFailedCaptureTransaction() {
		$transaction = static::transactions()['02-failed'];
		$litle       = TestHelper::mockLitleRequest('captureTransaction', $transaction['response']);

		$result = (new CaptureRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('323462', $result->getLitleTxnId());
		$this->assertFalse($result->isApproved());
	}

	/**
	 * Failed Capture Transaction missing TXN id
	 *
	 * @expectedException Petflow\Litle\Exception\MissingRequestParameterException
	 */
	public function testFailedCaptureTransactionMissingTxnId() {
		$transaction = static::transactions()['03-missing-txnid'];
		$litle       = TestHelper::mockLitleRequest('captureTransaction', $transaction['response']);

		$result = (new CaptureRequest([], [], $litle))->make($transaction['request']);
	}

	/**
	 * Transactions for this Test
	 */
	private static function transactions() {
		return [
			'01-approved' => [
				'request'  => [
					'litleTxnId' => '323462'
				],
				'response' => TestHelper::makeCaptureXMLResponse([], [
					'litleTxnId' => '323462',
					'orderId' => '10110',
					'response' => '000',
					'message' => 'Approved'
				])
			],
			'02-failed' => [
				'request'  => [
					'litleTxnId' => '323462'
				],
				'response' => TestHelper::makeCaptureXMLResponse([], [
					'litleTxnId'   => '323462',
					'response'     => '305',
					'message' 	   => 'Expired Card',
					'responseTime' => '2013-07-01T11:37:04',
					'postDate' 	   => null
				])
			],
			'03-missing-txnid' => [
				'request'  => [],
				'response' => TestHelper::makeCaptureXMLResponse([], [])
			]
		];
	}
}