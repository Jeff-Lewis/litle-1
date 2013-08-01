<?php namespace Petflow\Litle\Transaction\Response;

/**
 * Credit Response
 */
class CreditResponse extends TransactionResponse {

    /**
     * Constructor Overrides
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'creditResponse', $mode);
    }
}