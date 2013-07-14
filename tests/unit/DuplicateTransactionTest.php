<?php

use Petflow\Litle\Transaction\SaleTransaction as SaleTransaction;

/**
 * Test for Duplicate Transactions
 */
class DuplicateTransactionTest extends UnitTestCase {

	/**
	 * Testing for a Duplicate Sales Transaction
	 */
	public function testDuplicateSalesTransaction() {
		$litle = Mockery::mock('LitleOnlineRequest')
			->shouldReceive('saleRequest')
			->andReturn($this->duplicateResponseXML())
			->getMock();

		try {
			$response = (new SaleTransaction([], [], $litle))->make($this->duplicateTransactionRequest());

		} catch (Petflow\Litle\Exception\DuplicateTransactionException $e) {
			$response_data = $e->getResponseData();

			$this->assertArrayHasKey('litle_transaction_id', $response_data);
			$this->assertEquals('foo', $response_data['litle_transaction_id']);
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