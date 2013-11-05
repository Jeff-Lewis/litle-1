<?php

use Petflow\Litle\Transaction\Request\RegisterTokenRequest;
use Petflow\Litle\Utility\TestHelper;

/**
 * Test Register Token
 */
class RegisterTokenTest extends UnitTestCase {

	/**
	 * Register a Credit Card
	 */
	public function testSuccessfullyRegisterCreditCardToken() {
		$litle  = TestHelper::mockLitleRequest('registerTokenRequest', static::transactions()['01-approved-for-cc']['response']);
		$result = (new RegisterTokenRequest(['mode' => 'production'], [], $litle))->make(static::transactions()['01-approved-for-cc']['request']);

		$this->assertTrue($result->isApproved());
		$this->assertEquals('4355236262', $result->getLitleToken());
		$this->assertEquals('123456', $result->getCardBin());
		$this->assertEquals('VI', $result->getCardType());
	}

	/**
	 * Register Token
	 */
	public function testSuccessfullyRegisterRegistrationIdToken() {
		$litle  = TestHelper::mockLitleRequest('registerTokenRequest', static::transactions()['02-approved-for-registrationid']['response']);
		$result = (new RegisterTokenRequest(['mode' => 'production'], [], $litle))->make(static::transactions()['02-approved-for-registrationid']['request']);
		
		$this->assertTrue($result->isApproved());
		$this->assertEquals('4355236262', $result->getLitleToken());
		$this->assertEquals('4355236262', $result->getLitleToken());
		$this->assertEquals('123456', $result->getCardBin());
		$this->assertEquals('VI', $result->getCardType());
	}

	/**
	 * Raises Exception w/ Both Account # and Registration id
	 */
	public function testExceptionRaisedWhenProvidingBothAccountNumberAndRegistrationId() {
		try {
			$litle  = TestHelper::mockLitleRequest('registerTokenRequest', static::transactions()['05-both-account-and-registration-exceptio']['response']);
			$result = (new RegisterTokenRequest(['mode' => 'production'], [], $litle))->make(static::transactions()['05-both-account-and-registration-exception']['request']);
			
			// shouldnh't get here!
			$this->assertTrue(false);

		} catch (\Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Missing Parameter Failures
	 *
	 * @dataProvider missingParameterProvider
	 * @expectedException MissingRequestParameterException
	 */
	public function testMissingParameterFailures($request, $response) {
		$litle  = TestHelper::mockLitleRequest('registerTokenRequest', $response);
		$result = (new RegisterTokenRequest(['mode' => 'production'], [], $litle))->make($request);
	}


	/**
	 * Missing Parameter Provider
	 */
	public static function missingParameterProvider() {
		return array_slice(static::transactions(), 2, 2);
	}

	/**
	 * Transactions
	 */
	private static function transactions() {
		return [
			'01-approved-for-cc' => [
				'request'  => [
					'orderId' 	     => '23423',
					'accountNumber'  => '235235252'
				],
				'response' => TestHelper::makeRegisterTokenXMLResponse(['id' => '23423'], [
					'litleTxnId' 	=> '23526612436',
					'litleToken'    => '4355236262',
					'orderId' 		=> '23423',
					'response' 		=> '000',
					'bin'           => '123456',
					'type'          => 'VI',
					'message' 		=> 'Approved'
				])
			],
			'02-approved-for-registrationid' => [
				'request' => [
					'orderId' 				=> '23423',
					'paypageRegistrationId' => '235235252'
				],
				'response' => TestHelper::makeRegisterTokenXMLResponse(['id' => '23423'], [
					'litleTxnId' 	=> '23526612436',
					'litleToken'    => '4355236262',
					'orderId' 		=> '23423',
					'response' 		=> '000',
					'bin'           => '123456',
					'type'          => 'VI',
					'message' 		=> 'Approved'
				])
			],
			'03-missing-orderid' => [
				'request' => [
					'accountNumber' => '23525151521251',
				],
				'response' => []
			],
			'04-missing-account-and-registration' => [
				'request' => [
					'orderId' => '23423'
				],
				'response' => []
			],		
			'05-both-account-and-registration-exception' => [
				'request' => [
					'orderId' 				=> '23423',
					'accountNumber' 		=> '2352525252',
					'paypageRegistrationId' => '2352525225'
				],
				'response' => []
			]
		];
	}
}