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
    public function __construct($raw_response) {
        parent::__construct($raw_response);

        $this->auth_code = \XMLParser::getNode($raw_response, 'authCode');

        // AVS results
        try {
            $this->avs = ResponseCode\AVSResponseCode::code(\XMLParser::getNode($raw_response, 'avsResult'));

        } catch (Exception\UnknownResponseCodeException $e) {
            $this->avs = null;
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

        return $this->avs['code'] === '01' || $this->avs['code'] === '02';
    }
}