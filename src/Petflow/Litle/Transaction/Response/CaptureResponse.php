<?php namespace Petflow\Litle\Transaction\Response;

/**
 * Capture Response
 */
class CaptureResponse extends TransactionResponse {

    /**
     * Constructor Overrides
     */
    public function __construct($raw_response) {
        parent::__construct($raw_response);

        $this->post_date = (new \DateTime(\XMLParser::getNode($raw_response, 'postDate')))->format('Y-m-d H:i:s');   
    }
}