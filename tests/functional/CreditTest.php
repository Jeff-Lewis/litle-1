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

		$this->assertEquals('1', $credit->getOrderId());
		$this->assertEquals('000', $credit->getCode());
	}

	/**
	 * TRansactions
	 */
	public static function transactions($key) 
	{
		$trans = [
			'approved' => [
				'id'         => '1',
				'amount'     => '2000',
				'litleTxnId' => '100000000000000002'
			]
		];

		return $trans[$key];
	}
}