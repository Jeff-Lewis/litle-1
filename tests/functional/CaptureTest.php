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

		$this->assertEquals('44', $capture->getOrderId());
		$this->assertEquals('000', $capture->getCode());
	}

	/**
	 * TRansactions
	 */
	public static function transactions($key) 
	{
		$trans = [
			'approved' => [
				'id'    	 => '44',
				'orderId'    => '44',
				'amount' 	 => '140.00',
				'litleTxnId' => '123456789123456000'
			]
		];

		return $trans[$key];
	}
}