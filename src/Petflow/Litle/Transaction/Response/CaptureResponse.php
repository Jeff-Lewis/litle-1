<?php namespace Petflow\Litle\Transaction\Response;

/**
 * Capture Response
 */
class CaptureResponse extends TransactionResponse {

    /**
     * Constructor Overrides
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'captureResponse', $mode);

        $this->post_date = (new \DateTime(\XMLParser::getNode($raw_response, 'postDate')))->format('Y-m-d H:i:s');   
    }
}