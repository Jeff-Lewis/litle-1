<?php

use Petflow\Litle\Transaction\Batch;

class BatchTokenRegistrationTest extends CertificationTestCase {
		
	/**
	 * [testCreateBatchOfTokens description]
	 * @return [type] [description]
	 */
	public function testCreateBatchOfTokensReturnsArray() {
		$reg    = new Batch\TokenRegistration(static::getParams());
		$result = $reg->send(static::cards());
var_export($result);
		$this->assertInternalType('array', $result);
	}

	/**
	 * Cards
	 */
	private static function cards() {
		return [
			[	
				'payment_option_id' => '123456',
				'card' => '4005101001000002'
			],
			[			
				'payment_option_id' => '123457',
				'card' => '4005101001000003'
			],
			[	
				'payment_option_id' => '123458',
				'card' => '4005101001000004'
			],
			[	
				'payment_option_id' => '123459',
				'card' => '4005101001000005'
			]
		];
	}
}
