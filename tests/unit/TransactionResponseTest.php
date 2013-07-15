<?php

use Petflow\Litle\Transaction\SaleTransaction as SaleTransaction;

/**
 * Test for Duplicate Transactions
 */
class TransactionResponseTest extends UnitTestCase {

    /**
     * Testing for a Duplicate Sales Transaction
     */
    public function testApprovedSalesTransactionResponse() {
        $litle = Mockery::mock('LitleOnlineRequest')
            ->shouldReceive('saleRequest')
            ->andReturn($this->approvedResponseXML())
            ->getMock();

        $response = (new SaleTransaction([], [], $litle))->make($this->transactionRequest());

    //     $this->assertEquals('000', $response->getCode());
    //     $this->assertEquals('2013-07-01 11:37:04', $response->getTime());
    //     $this->assertEquals('Approved', $response->getDetails()['message']);
    //     $this->assertEquals('Litle sale transaction information: Request ID is bar, PNREF is n/a, Authorization code is  , Result code is 000, Result Msg is "No action required."', (string) $response);
    }

    /**
     * An Unknown Sales Transaction
     */
    public function testUnknownSalesTransactionResponse() {
    //     $litle = Mockery::mock('LitleOnlineRequest')
    //         ->shouldReceive('saleRequest')
    //         ->andReturn($this->unknownResponseXML())
    //         ->getMock();

    //     $response = (new SaleTransaction([], [], $litle))->make($this->transactionRequest());

    //     $this->assertEquals('foobar', $response->getCode());
    //     $this->assertEquals('2013-07-01 11:37:04', $response->getTime());
    //     $this->assertEquals('Unknown response code provided: foobar', $response->getDetails());
    //     $this->assertEquals('Litle sale transaction information: Request ID is bar, PNREF is n/a, Authorization code is  , Result code is foobar, Result Msg is "Unknown response code provided: foobar"', (string) $response);
    }

    /**
     * Duplicate Transaction Request
     */
    private function transactionRequest() {
        return [
            'amount'        => 10100,
            'orderId'       => '1',
            'orderSource'   => 'ecommerce',
            'billToAddress' => [
                'name'          => 'John Smith',
                'addressLine1'  => '1 Main St.',
                'city'          => 'Burlington',
                'state'         => 'MA',
                'zip'           => '01803-3747',
                'country'       => 'US'
            ],
            'card' => [
                'number'                => '4457010000000009',
                'expDate'               => '0114',
                'cardValidationNum'     => '349',
                'type'                  => 'VI'
            ]
        ];
    }

    /**
     * XML Response for Duplicate Transaction
     */
    private function approvedResponseXML() {
        $dom = new DOMDocument();
        $dom->loadXML(
            trim('
                <litleOnlineResponse version="8.15" xmlns="http://www.litle.com/schema" message="Valid Format">
                    <saleResponse id="foo" reportGroup="default">
                        <litleTxnId>foobarbaz</litleTxnId>
                        <orderId>bar</orderId>
                        <response>000</response>
                        <responseTime>2013-07-01T11:37:04</responseTime>
                        <postDate></postDate>
                        <message></message>
                        <authCode></authCode>
                    </saleResponse>
                </litleOnlineResponse>
            ')
        );
        return $dom;
    }

    /**
     * XML Response for an Unknown Transaction Result
     */
    private function unknownResponseXML() {
        $dom = new DOMDocument();
        $dom->loadXML(
            trim('
                <litleOnlineResponse version="8.15" xmlns="http://www.litle.com/schema" message="Valid Format">
                    <saleResponse id="foo" reportGroup="default">
                        <litleTxnId>foobarbaz</litleTxnId>
                        <orderId>bar</orderId>
                        <response>foobar</response>
                        <responseTime>2013-07-01T11:37:04</responseTime>
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