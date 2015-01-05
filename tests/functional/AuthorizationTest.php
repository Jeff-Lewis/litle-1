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
     * Test the account updater element
     */
    public function testAccountUpdater() {
        $authorization = (new AuthorizationRequest(static::getParams(), []))->make(static::transactions('account_updater'));
        $this->assertArrayHasKey('original', $authorization->getUpdatedCardInformation());
        $this->assertArrayHasKey('corrected', $authorization->getUpdatedCardInformation());

        $this->assertNotEmpty($authorization->getUpdatedCardInformation()['original']);
        $this->assertNotEmpty($authorization->getUpdatedCardInformation()['corrected']);

        $this->assertTrue($authorization->cardRequiresUpdate());
    }

    /**
     * Transactions
     */
    public static function transactions($key)
    {
        $trans = [
            'approved' => [ 
                'id'        => '22',
                'orderId'   => '22',
                'amount'    => '1000',
                'card'      => [
                    'number'    => '374322062409525',
                    'type'      => 'AX',
                    'expDate'   => '0315',
                    'cardValidationNumber' => ''
                ],
                'billToAddress' => [
                    'name' => 'Johnny Sucks',
                    'zip' => 12561
                ]
            ],

            'account_updater' => [
                'id'        => '22',
                'orderId'   => '22',
                'amount'    => '1000',
                'card'      => [
                    'number'    => '4100117890123000',
                    'type'      => 'AX',
                    'expDate'   => '0315',
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