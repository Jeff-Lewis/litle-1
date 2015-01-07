<?php namespace Petflow\Litle\Transaction\Response;

use Petflow\Litle\ResponseCode;
use Petflow\Litle\Exception;

use XMLParser;

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
     * Account Updater element
     *
     * Contains information needed to update the user's card
     */
    public $updater_element;

    /**
     * Construction
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'authorizationResponse', $mode);

        $this->auth_code       = trim(XMLParser::getNode($raw_response, 'authCode'));
        $this->avs             = $this->processAvsResult($raw_response, $mode);
        $this->updater_element = $this->parseAccountUpdaterDetails(XMLParser::xmlDOMDocumentToArray($raw_response));
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
     * Get Updater Element
     */
    public function getUpdatedCardInformation() {
        return $this->updater_element;
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
     * Determine if the card send to Litle in the request needs an update (i.e. cc expiration date change)
     * 
     * @return boolean
     */
    public function cardRequiresUpdate() {
        if (!empty($this->getUpdatedCardInformation()['original'])) {
            return true;
        }

        return false;
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

    /**
     * Parse out the account updater details
     * 
     * @param  array  $data
     * @return array
     */
    private function parseAccountUpdaterDetails(array $data) {
        if (!isset($data['litleOnlineResponse']['authorizationResponse']['accountUpdater'])) {
            return [
                'original'  => [],
                'corrected' => []
            ];
        }

        $updater_element = $data['litleOnlineResponse']['authorizationResponse']['accountUpdater'];

        return [
            'original'  => [
                'type'    => $updater_element['originalCardInfo']['type'],
                'number'  => $updater_element['originalCardInfo']['number'],
                'expDate' => $updater_element['originalCardInfo']['expDate']
            ],
            'corrected' => [
                'type'    => $updater_element['newCardInfo']['type'],
                'number'  => $updater_element['newCardInfo']['number'],
                'expDate' => $updater_element['newCardInfo']['expDate']
            ]
        ];
    }
}