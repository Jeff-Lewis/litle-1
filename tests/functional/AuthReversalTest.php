<?php

use Petflow\Litle\Transaction\Request\AuthorizationReversalRequest;

/**
 * Capture Functional Test
 */
class AuthReversalTest extends FunctionalTestCase {
	
	/**
	 * Test Approved Capture
	 */
	public function testApproved()
	{
		$reversal = (new AuthorizationReversalRequest(static::getParams(), []))->make(static::transactions('approved'));

		$this->assertEquals('1', $reversal->getOrderId());
		$this->assertEquals('000', $reversal->getCode());
	}

	/**
	 * TRansactions
	 */
	public static function transactions($key) 
	{
		$trans = [
			'approved' => [
				'id' => 1,
				'amount' => 10100,
				'litleTxnId' => '230523523212341'
			]
		];

		return $trans[$key];
	}
}