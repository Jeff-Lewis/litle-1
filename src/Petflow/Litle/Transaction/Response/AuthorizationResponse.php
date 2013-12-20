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

        // AVS results
        try {
            $fraud_nodes = $raw_response->getElementsByTagName('avsResult');

            if ($fraud_nodes->length > 0) {
                foreach ($fraud_nodes as $node) {  
                    if ($node->nodeName == 'avsResult') {
                        $this->avs = ResponseCode\AVSResponseCode::code(
                            $node->nodeValue
                        );
                    }
                }

                if (!$this->avs) {
                    $this->avs = ResponseCode\AVSResponseCode::code('34');
                }

            } else {
                if ($mode === 'sandbox') {
                    $this->avs = ResponseCode\AVSResponseCode::code('01');

                } else {
                    $this->avs = ResponseCode\AVSResponseCode::code('34');
                }
            }

        } catch (UnknownResponseCodeException $e) {
            $this->avs = ResponseCode\AVSResponseCode::code('34');
        }
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
}