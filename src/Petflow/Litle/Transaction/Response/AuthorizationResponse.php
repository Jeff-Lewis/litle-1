<?php namespace Petflow\Litle\Transaction\Response;

use Petflow\Litle\ResponseCode;
use Petflow\Litle\Exception;

/**
 * Authorization Response
 */
class AuthorizationResponse extends TransactionResponse {

    /**
     * AVS Results
     */
    protected $avs;

    /**
     * Auth Code
     */
    protected $auth_code;

    /**
     * Construction
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'authorizationResponse', $mode);

        $this->auth_code = trim(\XMLParser::getNode($raw_response, 'authCode'));
        $this->avs       = $this->processAvsResult($raw_response, $mode);
    }

    /**
     * Get AVS Response
     */
    public function getAvs() {
        return $this->avs;
    }

    /**
     * Get Auth Code
     */
    public function getAuthCode() {
        return $this->auth_code;
    }

    /**
     * Is AVS Approved
     */
    public function isAVSApproved() {
        if (!isset($this->avs['code'])) {
            return false;
        }

        return in_array($this->avs['actual_code'], ['X', 'Y', 'A', 'W', 'Z']);
    }

    /**
     * AVS Response String
     */
    public function getAvsResponseString() {
        return $this->avs;
    }

    /**
     * Response String Override
     */
    public function getResponseString() {
        return parent::getResponseString().' with authcode of '.$this->getAuthCode();
    }

    /**
     * Process the avs response.
     * 
     * @param  DOMDocument $raw_response
     * @return null
     */
    protected function processAvsResult(\DOMDocument $raw_response, $mode = 'sandbox') {
        try {
            $fraud_nodes = $raw_response->getElementsByTagName('avsResult');

            if ($fraud_nodes->length > 0) {
                foreach ($fraud_nodes as $node) {  
                    if ($node->nodeName == 'avsResult') {
                        return ResponseCode\AVSResponseCode::code(
                            $node->nodeValue
                        );
                    }
                }

                if (!$this->avs) {
                    return ResponseCode\AVSResponseCode::code('34');
                }

            } else {
                if ($mode === 'sandbox') {
                    return ResponseCode\AVSResponseCode::code('01');

                } else {
                    return ResponseCode\AVSResponseCode::code('34');
                }
            }

        } catch (UnknownResponseCodeException $e) {
            return ResponseCode\AVSResponseCode::code('34');
        }
    }
}