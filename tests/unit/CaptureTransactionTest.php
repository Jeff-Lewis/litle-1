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
		$litle       = TestHelper::mockLitleRequest('captureRequest', $transaction['response']);

		$result = (new CaptureRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('1', $result->getOrderId());
		$this->assertEquals('323462', $result->getLitleTxnId());
		$this->assertTrue($result->isApproved());
	}

	/**
	 * Failed Capture Transaction
	 */
	public function testFailedCaptureTransaction() {
		$transaction = static::transactions()['02-failed'];
		$litle       = TestHelper::mockLitleRequest('captureRequest', $transaction['response']);

		$result = (new CaptureRequest([], [], $litle))->make($transaction['request']);

		$this->assertEquals('2', $result->getOrderId());
		$this->assertEquals('323462', $result->getLitleTxnId());
		$this->assertFalse($result->isApproved());
	}

	/**
	 * Failed Capture Transaction missing TXN id
	 *
	 * @expectedException MissingRequestParameterException
	 * @dataProvider missingParametersProvider
	 */
	public function testFailedCaptureTransactionMissingTxnId($request, $response) {
		$litle = TestHelper::mockLitleRequest('captureRequest', $response);
		$result = (new CaptureRequest([], [], $litle))->make($request);
	}

	/**
	 * Missing Parameter Tests
	 */
	public static function missingParametersProvider() {
		return array_slice(static::transactions(), 2);
	}

	/**
	 * Transactions for this Test
	 */
	private static function transactions() {
		return [
			'01-approved' => [
				'request'  => [
					'id'         => '1',
					'litleTxnId' => '323462',
					'amount'     => '10100'
				],
				'response' => TestHelper::makeCaptureXMLResponse(['id' => '1'], [
					'litleTxnId' => '323462',
					'response' 	 => '000',
					'message'    => 'Approved',
					'responseTime' => '2013-07-01T11:37:04'
				])
			],
			'02-failed' => [
				'request'  => [
					'id'         => '2',
					'litleTxnId' => '323462',
					'amount'     => '101.00'
				],
				'response' => TestHelper::makeCaptureXMLResponse(['id' => '2'], [
					'litleTxnId'   => '323462',
					'response'     => '305',
					'message' 	   => 'Expired Card',
					'responseTime' => '2013-07-01T11:37:04',
					'postDate' 	   => null
				])
			],
			'03-missing-order-id' => [
				'request'  => [
					'litleTxnId' => '1000000000000253',
					'amount'     => '250.00'
				],
				'response' => TestHelper::makeCaptureXMLResponse([], [])
			],
			'04-missing-txnid' => [
				'request'  => [
					'id' 		=> '4',
					'amount'    => '250.00'
				],
				'response' => TestHelper::makeCaptureXMLResponse([], [])
			],
			'05-missing-amount' => [
				'request'  => [
					'id' 		 => '5',
					'litleTxnId' => '1000000000000253'
				],
				'response' => TestHelper::makeCaptureXMLResponse([], [])
			],
		];
	}
}