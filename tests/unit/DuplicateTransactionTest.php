<?php

use Petflow\Litle\Transaction\SaleTransaction as SaleTransaction;

/**
 * Test for Duplicate Transactions
 */
class DuplicateTransactionTest extends UnitTestCase {

	/**
	 * Testing for a Duplicate Sales Transaction (with try/catch)
	 */
	public function testDuplicateSalesTransactionFlags() {
			$litle = Mockery::mock('LitleOnlineRequest')
			->shouldReceive('saleRequest')
			->andReturn($this->duplicateResponseXML())
			->getMock();

		try {
			$response = (new SaleTransaction([], [], $litle))->make($this->duplicateTransactionRequest());
			
		} catch (\Petflow\Litle\Exception\DuplicateTransactionException $e) {
			$this->assertInstanceOf('Petflow\Litle\Transaction\TransactionResponse', $e->getResponse());
			$this->assertTrue($e->getResponse()->isDuplicate());	
		}
	}

	/**
	 * Duplicate Transaction Request
	 */
	private function duplicateTransactionRequest() {
		return [
			'amount' 		=> 10100,
			'orderId' 		=> '1',
			'orderSource'	=> 'ecommerce',
			'billToAddress' => [
				'name' 			=> 'John Smith',
				'addressLine1' 	=> '1 Main St.',
				'city' 			=> 'Burlington',
				'state' 		=> 'MA',
				'zip' 			=> '01803-3747',
				'country' 		=> 'US'
			],
			'card' => [
				'number' 				=> '4457010000000009',
				'expDate' 				=> '0114',
				'cardValidationNum'		=> '349',
				'type' 					=> 'VI'
			]
		];
	}

	/**
	 * XML Response for Duplicate Transaction
	 */
	private function duplicateResponseXML() {
		return $this->makeSaleXMLResponse(
			[
				'duplicate' => 'true', 
				'id' => 'foo'
			],	
			[
				'litleTxnId' => 'foo',
				'orderId' => 'foo',
				'response' => '000',
				'responseTime' => '',
				'message' => '',
				'authCode' => ''
			]
		);
	}

}