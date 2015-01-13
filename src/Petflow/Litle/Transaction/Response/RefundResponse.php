<?php namespace Petflow\Litle\Transaction\Response;

use Petflow\Litle\ResponseCode;
use Petflow\Litle\Exception;

use XMLParser;

/**
 * Refund Response
 */
class RefundResponse extends TransactionResponse {

    /**
     * Construction
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'authorizationResponse', $mode);
    }

}