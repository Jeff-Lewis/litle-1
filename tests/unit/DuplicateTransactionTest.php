<?php

use Petflow\Litle\Transaction\SaleTransaction as SaleTransaction;

/**
 * Test for Duplicate Transactions
 */
class DuplicateTransactionTest extends FunctionalTestCase {

	/**
	 * Tear Down
	 */
	public function tearDown() {
		Mockery::close();
	}

	/**
	 * Testing for a Duplicate Sales Transaction
	 *
	 * @expectedException Petflow\Litle\Exception\DuplicateTransactionException
	 */
	public function testDuplicateSalesTransactionException() {
		$litle = Mockery::mock('LitleOnlineRequest')
			->shouldReceive('saleRequest')
			->andReturn($this->duplicateResponseXML())
			->getMock();

		$response = (new SaleTransaction([], [], $litle))->make($this->duplicateTransactionRequest());
	}

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
		$dom = new DOMDocument();
		$dom->loadXML(
			trim('
				<litleOnlineResponse version="8.15" xmlns="http://www.litle.com/schema" response="0" message="Valid Format">
					<saleResponse id="foo" duplicate="true" reportGroup="default">
						<litleTxnId>foo</litleTxnId>
						<orderId>foo</orderId>
						<response>000</response>
						<responseTime></responseTime>
						<postDate></postDate>
						<message></message>
						<authCode></authCode>
					</saleResponse>
				</litleOnlineResponse>
			')
		);
		return $dom;
	}

}