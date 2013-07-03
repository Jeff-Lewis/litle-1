<?php

use Petflow\Litle\ResponseCode\AVSResponseCode as AVSResponseCode;

/**
 * Testing the ResponseCode class and its types
 */
class ResponseCodeTest extends UnitTestCase {

	/**
	 * Test Get a Valid AVS Response Code
	 */
	public function testGetValidAVSResponseCode() {
		$code = AVSResponseCode::code('14');

		$this->assertInternalType('array', $code);
		$this->assertArrayHasKey('description', $code);
		$this->assertEquals('Postal code matches, address not verified', $code['description']);
	}

	/**
	 * Test CV (Card Validation) Response Codes
	 */
	public function testCVResponseCodes() {
		$code = CVResponseCode::code('S');

		$this->assertInternalType('array', $code);
		$this->assertArrayHasKey('description', $code);
		$this->assertEquals('CVV2/CVC2/CID should be on the card, but the merchant has indicated CVV2/CVC2/CID is not present', $code['description']);		
	}
} 