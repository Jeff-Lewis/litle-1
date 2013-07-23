<?php

use Petflow\Litle\Transaction\Request\AuthorizationRequest;

/**
 * Authorization Transactions Test
 */
class AuthorizationTest extends FunctionalTestCase {

	/**
	 * Test Approved Authorization
	 */
	public function testApprovedAuthorization() 
	{
		$authorization = (new AuthorizationRequest(static::getParams(), []))->make(static::transactions('approved'));
		
		$this->assertEquals('01', $authorization->getAVS()['code']);
		$this->assertEquals('000', $authorization->getCode());
	}

	/**
	 * Transactions
	 */
	public static function transactions($key)
	{
		$trans = [
			'approved' => [
				'orderId' 	=> 2684995,
				'amount' 	=> '1000',
				'card'   	=> [
					'number' 	=> '374322062409525',
					'type' 		=> 'AX',
					'expDate' 	=> '0315',
					'cardValidationNumber' => ''
				],
				'billToAddress' => [
					'zip' => 12561
				]
			]
		];

		return $trans[$key];
	}

}