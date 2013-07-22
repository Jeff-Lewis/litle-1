<?php

use Petflow\Litle\Transaction\Request\CaptureRequest;

/**
 * Capture Functional Test
 */
class CaptureTest extends FunctionalTestCase {
	
	/**
	 * Test Approved Capture
	 */
	public function testApproved()
	{
		$capture = (new CaptureRequest(static::getParams(), []))->make(static::transactions('approved'));

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