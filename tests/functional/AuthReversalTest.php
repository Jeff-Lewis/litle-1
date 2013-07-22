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
		$capture = (new AuthorizationReversalRequest(static::getParams(), []))->make(static::transactions('approved'));

		$this->assertEquals('000', $capture->getCode());
	}

	/**
	 * TRansactions
	 */
	public static function transactions($key) 
	{
		$trans = [
			'approved' => [
				'orderId' => 1,
				'litleTxnId' => '230523523212341'
			]
		];

		return $trans[$key];
	}
}