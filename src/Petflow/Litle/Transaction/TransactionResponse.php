<?php namespace Petflow\Litle\Transaction\Response;

use Petflow\Litle\ResponseCode\TransactionResponseCode as TransactionResponseCode;
use Petflow\Litle\Exception\DuplicateTransactionException as DuplicateTransactionException;

/**
 * Transaction Response
 *
 * @author Nate Krantz <nate@petflow.com>
 */
abstract class TransactionResponse {

    protected $order_id;
    protected $code;
    protected $auth_code;
    protected $details;
    protected $time;
    protected $is_duplicate = false;

    protected $data;
    protected $debug;

    protected $xml_node_name;

    /**
     * Create a Response
     *
     * When creating a transaction response, the constructor will look 
     * for the appropriate fields in the raw response XML and then parse
     * them out into the corresponding members of this class.
     */
    public function __construct($raw_response_data, $node_name, $mode='sandbox') {
        $this->xml_node_name = $node_name;

        $this->setCode(\XMLParser::getNode($raw_response_data, 'response'));
        
        $this->order_id     = \XMLParser::getAttribute($raw_response_data, $this->xml_node_name, 'id');
        $this->litle_txn_id = \XMLParser::getNode($raw_response_data, 'litleTxnId');
        $this->time         = (new \DateTime(\XMLParser::getNode($raw_response_data, 'responseTime')))->format('Y-m-d H:i:s');

        $this->debug = $raw_response_data;
        $this->mode = $mode;
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
        $this->details = TransactionResponseCode::code($code);
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function getCode() {
        return $this->code;
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

    public function isApproved() {
        return $this->code === '000' && $this->details['type'] === 'approved';
    }

    public function debug() {
        return $this->debug;
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
     * Get Response String
     */
    public function getResponseString() {
        return $this->getCode() . ' :: '.$this->details;
    }
}