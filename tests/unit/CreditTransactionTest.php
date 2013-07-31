<?php

use Petflow\Litle\Transaction\Request\CreditRequest;
use Petflow\Litle\Utility\TestHelper;

/**
 * Test Capture Transaction
 */
class CreditTransactionTest extends UnitTestCase {
	
	/**
	 * Approved Credit Transaction
	 */
	public function testApprovedCreditTransaction () {
		$transaction = static::transactions()['01-approved'];
		$litle       = TestHelper::mockLitleRequest('creditRequest', $transaction['response']);

		$result = (new CreditRequest(['mode' => 'production'], [], $litle))->make($transaction['request']);

		$this->assertEquals('323462', $result->getLitleTxnId());
		$this->assertTrue($result->isApproved());
	}

	/**
	 * @dataProvider missingParameterProvider
	 * @expectedException Petflow\Litle\Exception\MissingRequestParameterException
	 */
	public function testFailedCreditTRansactionMissingParameter($request, $response) {
		$litle  = TestHelper::mockLitleRequest('creditRequest', $response);
		$result = (new CreditRequest(['mode' => 'production'], [], $litle))->make($request);
	}

	/**
	 * Missing Parameter Test Cases
	 */
	public static function missingParameterProvider() {
		return array_slice(static::transactions(), 1);
	}

	/**
	 * Transaction Sources
	 */
	private static function transactions() {
		return [
			'01-approved' => [
				'request'  => [
					'litleTxnId' => '323462',
					'amount'     => '200.00',
					'id'         => '01'
				],
				'response' => TestHelper::makeCreditXMLResponse([], [
					'litleTxnId' 	=> '323462',
					'orderId' 		=> '10110',
					'response' 		=> '000',
					'message' 		=> 'Approved'
				])
			],
			'02-empty-txnid' => [
				'request' => [
					'litleTxnId' => '',
					'amount' 	 => '50.00',
					'id'         => '02'
				],
				'response' => []
			],
			'03-missing-orderid' => [
				'request' => [
					'litleTxnId' => '23423',
					'amount' 	 => '50.00'
				],
				'response' => []
			],
			'04-missing-amount' => [
				'request' => [
					'litleTxnId' => '23423',
					'id' => '04'
				],
				'response' => []
			],		
		];
	}

}