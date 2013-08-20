<?php

use Petflow\Litle\Request;

/**
 * Token Registration Tests
 */
class TokenRegistrationTest extends CertificationTestCase {

	/**
	 * Successful Token Registraiton
	 */
	public function testSuccessfulTokenRegistration() {
		$response = (new Request\RegisterTokenRequest(static::getParams()))->make([
			'orderId' => '1'
			'accountNumber' => '4457119922390123'
		]);

		$this->assertTrue($response->isApproved());
		$this->assertEquals('801', $response->getCode());
		$this->assertEquals('0123', substr($response->getLitleToken(), -4));
		$this->assertEquals('VI', $response->getCardType());
		$this->assertEquals('445711', $response->getCardBin());
	}
}