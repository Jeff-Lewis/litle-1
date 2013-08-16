<?php

use Petflow\Litle\Transaction\Request\CreditRequest;

/**
 * Capture Functional Test
 */
class CreditTest extends FunctionalTestCase {
	
	/**
	 * Test Approved Capture
	 */
	public function testApproved()
	{
		$credit = (new CreditRequest(static::getParams(), []))->make(static::transactions('approved'));

		$this->assertEquals('33', $credit->getOrderId());
		$this->assertEquals('000', $credit->getCode());
	}

	/**
	 * TRansactions
	 */
	public static function transactions($key) 
	{
		$trans = [
			'approved' => [
				'id'         => '33',
				'amount'     => '2000',
				'litleTxnId' => '123456789123456000'
			]
		];

		return $trans[$key];
	}
}