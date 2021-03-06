<?php namespace Petflow\Litle\Transaction\Response;

/**
 * Authorization Reversal Response
 */
class AuthorizationReversalResponse extends TransactionResponse {

    /**
     * Constructor Overrides
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'authReversalResponse', $mode);
    }
}