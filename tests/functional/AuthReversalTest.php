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
		$reversal = (new AuthorizationReversalRequest(static::$params, []))->make(static::transactions('approved'));

		$this->assertEquals('11', $reversal->getOrderId());
		$this->assertEquals('000', $reversal->getCode());
	}

	/**
	 * TRansactions
	 */
	public static function transactions($key) 
	{
		$trans = [
			'approved' => [
				'id' 	     => '11',
				'orderId' 	 => '11',
				'amount' 	 => '10100',
				'litleTxnId' => '123456789123456000'
			]
		];

		return $trans[$key];
	}
}