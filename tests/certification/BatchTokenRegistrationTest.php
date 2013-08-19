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

		$this->assertInternalType('array', $result);
	}

	/**
	 * Cards
	 */
	private static function cards() {
		return [
			[	
				'payment_option_id' => '123456',
				'card' => '10000000001041'
			],
			[			
				'payment_option_id' => '123457',
				'card' => '10000000001042'
			],
			[	
				'payment_option_id' => '123458',
				'card' => '10000000001043'
			],
			[	
				'payment_option_id' => '123459',
				'card' => '10000000001044'
			]
		];
	}
}