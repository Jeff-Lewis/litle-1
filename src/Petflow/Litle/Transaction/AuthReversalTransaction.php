<?php namespace Petflow\Litle\Transaction;

use Petflow\Litle\Exception;
use Petflow\Litle\ResponseCode;

/**
 * Auth Reversal Transaction
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow.com 2013
 */
class AuthReversalTransaction extends Transaction {

	/**
	 * Make an Authoirzation Reversal Transaction
	 *
	 * When providing the parameters for an auth reversal, the
	 * request must have a txnId that was generated by a prior
	 * authorization request. Additionally, an actionReason
	 * can be provided for suspect fraud:
	 *
	 * 	'actionReason' => 'SUSPECT_FRAUD'
	 *
	 * The request should NOT contain an amount parameter, unless
	 * the clients system wants to do partial auth reversal.
	 */
	public function make($params) {

		// there must be a transaction id provided in the request
		// so that we can make the reversal
		if (!isset($params['txnId'])) {
			throw new Exception\MissingRequestParameterException('txnId');
		}

		// there must be an order id, which we use to tie the
		// request back to our merchant's system
		if (!isset($params['orderId'])) {
			throw new Exception\MissingRequestParameterException('orderId');
		}

		// stop any amounts, so that we can prevent partial
		// reversal until client system supports it
		if (isset($params['amount']))  {
			unset($params['amount']);
		}

		return $this->respond(
			$this->litle_sdk->authReversalRequest($params)
		);
	}

	/**
	 * Respond to the Auth Reversal Transaction
	 *
	 * The response to an auth reversal should contain the 
	 * orderId provided, response code, messsage, response time
	 * and the litletxnid assoicated with the reversal.
	 *
	 * We can further produce more information about the response
	 * by using the TransactionResponseCode with the provided
	 * response.
	 */
	public function respond($response) {
		$parsed = [
			'response' 				=> \XMLParser::getNode($response, 'response'),
			'message' 				=> \XMLParser::getNode($response, 'message'),
			'litle_transaction_id'  => \XMLParser::getNode($response, 'litleTxnId'),
			'order_id'				=> \XMLParser::getNode($response, 'orderId'),
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