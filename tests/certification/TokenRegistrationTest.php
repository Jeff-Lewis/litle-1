<?php

use Petflow\Litle\Transaction\Request;

/**
 * Token Registration Tests
 */
class TokenRegistrationTest extends CertificationTestCase {

	/**
	 * Successful Token Registraiton
	 *
	 * (note once this executes once, it will return 'already registered' since 
	 *  litles cert environment resets daily and not per request)
	 */
	public function testSuccessfulTokenRegistrationNewlyRegistered() {
		$response = (new Request\RegisterTokenRequest(static::getParams()))->make([
			'orderId' 		=> '1',
			'accountNumber' => '4457119922390123'
		]);

		$this->assertTrue($response->isApproved());
		$this->assertEquals('0123', substr($response->getLitleToken(), -4));
		$this->assertEquals('VI', $response->getCardType());
		$this->assertEquals('445711', $response->getCardBin());
	}

	/**
	 * Invalid CC
	 */
	public function testFailedTokenRegistrationDueToInvalidCC() {
		$response = (new Request\RegisterTokenRequest(static::getParams()))->make([
			'orderId' 		=> '2',
			'accountNumber' => '4457119999999999'
		]);
		
		$this->assertTrue(!$response->isApproved());
		$this->assertEquals(null, $response->getLitleToken());
		$this->assertEquals('820', $response->getCode());
	}

	/**
	 * Already Registered
	 */
	public function testSuccessfulTokenRegistrationAlreadyRegistered() {
		$response = (new Request\RegisterTokenRequest(static::getParams()))->make([
			'orderId' 		=> '3',
			'accountNumber' => '4457119922390123'
		]);

		$this->assertTrue($response->isApproved());
		$this->assertEquals('0123', substr($response->getLitleToken(), -4));
		$this->assertEquals('VI', $response->getCardType());
		$this->assertEquals('445711', $response->getCardBin());
	}

	/**
	 * Invalid Paypage registration
	 */
	public function testFailedPaypageRegistrationInvalid() {
		$response = (new Request\RegisterTokenRequest(static::getParams()))->make([
			'orderId' 				=> '4',
			'paypageRegistrationId' => 'RGFQNCt6U1d1M21SeVByVTM4dHlHb1FsVkUrSm pnWXhNY0o5UkMzRlZFanZiUHVnYjN1enJXbG1WS DF4aXlNcA=='
		]);

		$this->assertFalse($response->isApproved());
		$this->assertEquals('877', $response->getCode());
	}

	/**
	 * Expired Payapage Registration
	 */
	public function testFailedPaypageRegistrationExpired() {
		$response = (new Request\RegisterTokenRequest(static::getParams()))->make([
			'orderId' 				=> '5',
			'paypageRegistrationId' => 'cDZJcmd1VjNlYXNaSlRMTGpocVZQY1NWVXE4ZW 5UTko4NU9KK3p1L1p1Vzg4YzVPQVlSUHNITG1JN 2I0NzlyTg=='
		]);

		$this->assertFalse($response->isApproved());
		$this->assertEquals('878', $response->getCode());
	}

}
