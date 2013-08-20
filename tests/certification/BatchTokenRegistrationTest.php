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
				'card' => '4457119922390123'
			],
			[			
				'payment_option_id' => '123457',
				'card' => '4457119999999999'
			],
			[	
				'payment_option_id' => '123458',
				'card' => '4457119922390123'
			],
			[	
				'payment_option_id' => '123459',
				'card' => '5435101234510196'
			]
		];
	}
}
