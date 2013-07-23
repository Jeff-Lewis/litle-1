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
				'orderId'    => '2687233',
				'litleTxnId' => '898541439291894'
			]
		];

		return $trans[$key];
	}
}