<?php namespace Petflow\Litle\Transaction\Request;

use Petflow\Litle\ResponseCode;
use Petflow\Litle\Transaction\Response;

use MissingRequestParameterException;

/**
 * Refund Request Transaction
 *
 * @author Cory Crowther <cory@petflow.com>
 * @copyright Petflow.com 2015
 */
class RefundRequest extends TransactionRequest {

    const DEFAULT_ORDER_SOURCE = 'ecommerce';

    /**
     * Make a Refund Transaction
     *
     * Perform a refund request to Litle.
     *
     * @param  array $params
     *
     * @return Petflow\Litle\Transaction\Response\RefundResponse
     */
    public function make($params) {
        $this->checkForCardRequirements($params);

        // remove the comma!
        $params['amount']      = str_replace('.', '', (string) $params['amount']);
        $params['orderSource'] = self::DEFAULT_ORDER_SOURCE;

        return $this->respond($this->litle_sdk->creditRequest($params));
    }

    /**
     * Respond to a Refund Transaction
     *
     * After the transaction is made, this function will create the
     * response to be sent back to the client caller.
     *
     * @param  $raw_response
     *
     * @return Petflow\Litle\Transaction\Response\RefundResponse
     */
    public function respond($raw_response) {
        return new Response\RefundResponse($raw_response, $this->mode);
    }

    /**
     * Check for Requirements
     *
     * @param  array $params
     *
     * @throws Exception If requirements are missing.
     * 
     * @return null
     */
    private function checkForCardRequirements($params) {
        foreach (self::$required_fields as $field) {
            if (!isset($params[$field])) {
                throw new MissingRequestParameterException($field);
            }
        }
    }

    /**
     * Fields that are required in order to make the request
     * 
     * @var array
     */
    private static $required_fields = [
        'id',
        'orderId',
        'amount'
    ];
}