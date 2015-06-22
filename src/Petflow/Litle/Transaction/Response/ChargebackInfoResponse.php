<?php namespace Petflow\Litle\Transaction\Response;

class ChargebackInfoResponse extends TransactionResponse {

    /**
     * Constructor Overrides
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'chargeBackInfo', $mode);
    }
}