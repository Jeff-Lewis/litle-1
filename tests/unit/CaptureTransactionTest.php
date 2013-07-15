<?php

use Petflow\Litle\Transaction\CaptureTransaction as CaptureTransaction;

/**
 * Test Capture Transaction
 */
class CaptureTransactionTest extends UnitTestCase {
	
	/**
	 * Approved Capture Transaction
	 */
	public function testApprovedCaptureTransaction () {
		$transaction = static::transactions()['01-approved'];

		$litleOnlineRequest = Mockery::mock('litleOnlineRequest')
			->shouldReceive('captureTransaction')
			->once()
			->andReturn($transaction['response'])
			->mock();

		$result = (new CaptureTransaction([], [], $litleOnlineRequest))->make($transaction['request']);

		$this->assertEquals('323462', $result['litle_transaction_id']);
		$this->assertEquals('approved', $result['transaction_response']['type']);
	}

	/**
	 * Failed Capture Transaction missing TXN id
	 *
	 * @expectedException Petflow\Litle\Exception\MissingRequestParameterException
	 */
	public function testFailedCaptureTransactionMissingTxnId() {
		$result = (new CaptureTransaction([], [], Mockery::mock('litleOnlineRequest')))->make([]);
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
				'response' => static::makeCaptureXMLResponse([], [
					'litleTxnId' => '323462',
					'orderId' => '10110',
					'response' => '000',
					'message' => 'Approved'
				])
			]
		];
	}
}