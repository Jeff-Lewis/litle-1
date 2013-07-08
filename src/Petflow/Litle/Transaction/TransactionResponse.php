<?php namespace Petflow\Litle\Transaction;

use Petflow\Litle\ResponseCode\TransactionResponseCode as TransactionResponseCode;
use Petflow\Litle\Exception\DuplicateTransactionException as DuplicateTransactionException;

/**
 * Transaction Response
 *
 * @author Nate Krantz <nate@petflow.com>
 */
class TransactionResponse {

    protected $order_id;
    protected $code;
    protected $auth_code;
    protected $details;
    protected $time;
    protected $is_duplicate = false;

    protected $data;
    
    /**
     * Create a Response
     *
     * When creating a transaction response, the constructor will look 
     * for the appropriate fields in the raw response XML and then parse
     * them out into the corresponding members of this class.
     */
    public function __construct($raw_response_data) {
        $this->setCode(\XMLParser::getNode($raw_response_data, 'response'));

        $this->order_id = \XMLParser::getNode($raw_response_data, 'orderId');
        $this->time = (new \DateTime(\XMLParser::getNode($raw_response_data, 'responseTime')))->format('Y-m-d H:i:s');
        $this->auth_code = \XMLParser::getNode($raw_response_data, 'authCode');
        $this->litle_txn_id = \XMLParser::getNode($raw_response_data, 'litleTxnId');

        $this->is_duplicate = (bool) \XMLParser::getAttribute($raw_response_data, 'saleResponse', 'duplicate');

        // Duplicate response!
        if ($this->is_duplicate) {
            throw new DuplicateTransactionException($this, $raw_response_data);
        }
    }

    /**
     * Set a Response Code
     *
     * This is an internal function that will set the response code
     * and then attempt to lookup more information from the transaction
     * response code class.
     */
    private function setCode($code) {
        $this->code = $code;

        // set the details for this response, generated from the response code
        try {
            $this->details = TransactionResponseCode::code($code);
            
        } catch (\Exception $e) {
            $this->details = $e->getMessage();
        } 
    }

    public function getCode() {
        return $this->code;
    }

    public function getAuthCode() {
        return $this->auth_code;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getTime() {
        return $this->time;
    }

    public function getLitleTxnId() {
        return $this->litle_txn_id;
    }

    public function isDuplicate() {
        return $this->is_duplicate;
    }

    /**
     * Set Fields
     */
    public function __set($key, $value) {
        try {
            $this->$key = $value;
        } catch (Exception $e) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Get Fields
     */
    public function __get($key) {
        if (!isset($this->data[$key])) {

            if ($key === 'code') {
                return $this->getCode();
            }
            if ($key === 'time') {
                return $this->getTime();
            }
            if ($key === 'details') {
                return $this->getDetails();
            }

            return 'n/a';
        }

        return $this->data[$key];
    }

    /**
     * String Representation
     */
    public function __toString() {
        $details = $this->getDetails();

        if (isset($details['description'])) {
            $details = $details['description'];
        }

        return 'Litle sale transaction information: Request ID is '
            . $this->getOrderId() . ', PNREF is '
            . $this->getLitleTxnId . ', Authorization code is  '
            . $this->getAuthCode() . ', Result code is '
            . $this->getCode() . ', Result Msg is "'
            . $details.'"';
    }
}