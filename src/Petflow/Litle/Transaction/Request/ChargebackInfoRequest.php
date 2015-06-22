<?php namespace Petflow\Litle\Transaction\Request;
    
use Petflow\Litle\Transaction\Response;

use MissingRequestParameterException;

class ChargebackInfoRequest extends TransactionRequest {

    /**
     * Make a Chargeback Information Transaction
     *
     * @todo documentation here
     */
    public function make($params) {
        if (!isset($params['id'])) {
            throw new MissingRequestParameterException('id (alias for orderId)');
        }

        $params['amount'] = str_replace('.', '', (string) $params['amount']);
        
        return $this->respond(
            $this->litle_sdk->captureRequest($params)
        );
    }

    /**
     * Respond to a Capture Transaction
     *
     * @todo documentation here
     */
    public function respond($raw_response) {
        return new Response\ChargebackInfoResponse($raw_response, $this->mode);
    }

}