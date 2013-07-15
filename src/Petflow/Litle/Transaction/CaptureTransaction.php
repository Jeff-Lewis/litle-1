<?php namespace Petflow\Litle\Transaction;
	
use Petflow\Litle\ResponseCode;
use Petflow\Litle\Exception;

/**
 * Capture Transaction
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright Petflow.com 2013
 */
class CaptureTransaction extends TransactionRequest {

	/**
	 * Make a Capture Transaction
	 *
	 * @todo documentation here
	 */
	public function make($params) {

		// a capture transaction must have a txnid provided
		// or else we can't capture
		if (!isset($params['litleTxnId'])) {
			throw new Exception\MissingRequestParameterException('litleTxnId');
		}

		// do not allow an amount for now, when an amount is not
		// present in the request it will capture the full amount
		// we can change this if client system adapts for partial
		// capture
		if (isset($params['amount'])) {
			unset($params['amount']);
		}

		return $this->respond(
			$this->litle_sdk->captureTransaction($params)
		);
	}

	/**
	 * Respond to a Capture Transaction
	 *
	 * @todo documentation here
	 */
	public function respond($response) {
		$parsed = [
			'response' 				=> \XMLParser::getNode($response, 'response'),
			'message' 				=> \XMLParser::getNode($response, 'message'),
			'order_id'			    => \XMLParser::getNode($response, 'orderId'),
			'litle_transaction_id'  => \XMLParser::getNode($response, 'litleTxnId'),

			'post_date'			    => 
				(new \DateTime(\XMLParser::getNode($response, 'postDate')))
					->format('Y-m-d H:i:s'),			
			'response_time'			=> 
				(new \DateTime(\XMLParser::getNode($response, 'responseTime')))
					->format('Y-m-d H:i:s'),
		];

		// make the detailed response pulling in additional information from
		// the transaction respones code.
		try {
			$parsed['transaction_response'] = ResponseCode\TransactionResponseCode::code($parsed['response']);

		} catch (UnknownResponseCodeException $e) {
			$parsed['transaction_response'] = $e->getMessage();
		}

		return $parsed;
	}

}