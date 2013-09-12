<?php namespace Petflow\Litle\Transaction\Response;

/**
 * RegisterTokenResponse Response
 */
class RegisterTokenResponse extends TransactionResponse {

	/**
	 * Litle Token
	 * @var [type]
	 */
	protected $litleToken;

	/**
	 * Bin
	 * @var [type]
	 */
	protected $bin;

	/**
	 * Card Type
	 * @var [type]
	 */
	protected $type;

    /**
     * Constructor Overrides
     */
    public function __construct($raw_response, $mode='sandbox') {
        parent::__construct($raw_response, 'registerTokenResponse', $mode);

        $this->litleToken = trim(\XMLParser::getNode($raw_response, 'litleToken'));
        $this->bin        = trim(\XMLParser::getNode($raw_response, 'bin'));
        $this->type       = trim(\XMLParser::getNode($raw_response, 'type'));

        // ugh, because sometimes order_id is mirroed in the id attribute, but
        // othertimes its not.
        $this->order_id   = trim(\XMLParser::getNode($raw_response, 'orderId'));
    }

    /**
     * Token for Card
     */
    public function getLitleToken() {
    	return $this->litleToken;
    }

    /**
     * 6 Digit Bank Identification #
     */
    public function getCardBin() {
    	return $this->bin;
    }

    /**
     * Card Type (VI, MC, AX, etc)
     */
    public function getCardType() {
    	return $this->type;
    }
}