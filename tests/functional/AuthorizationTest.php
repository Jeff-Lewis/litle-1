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
		
		$this->assertEquals('22', $authorization->getOrderId());
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
				'id' 		=> '22',
				'orderId' 	=> '22',
				'amount' 	=> '1000',
				'card'   	=> [
					'number' 	=> '374322062409525',
					'type' 		=> 'AX',
					'expDate' 	=> '0315',
					'cardValidationNumber' => ''
				],
				'billToAddress' => [
					'name' => 'Johnny Sucks',
					'zip' => 12561
				]
			]
		];

		return $trans[$key];
	}

}