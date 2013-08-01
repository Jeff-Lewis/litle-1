<?php namespace Petflow\Litle\Transaction\Request;
	
use Petflow\Litle\ResponseCode;
use Petflow\Litle\Exception;
use Petflow\Litle\Transaction\Response;

/**
 * Credit Request
 *
 * This is a request for a credit, within the litle system. Credit requests in
 * litle need a txn id and an amount. While the amount is optional, its good
 * to specify it for better flexibility.
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright Petflow.com 2013
 */
class CreditRequest extends TransactionRequest {

	/**
	 * Make a Credit Request
	 *
	 * Send a credit request to the litle service, passing a transaction id, order id, and
	 * an amount. Will recieve an approved response when the request is made succesfully
	 * and approved by litle.
	 *
	 * @param Array $params The request parameters sending off to Litle
	 */
	public function make($params) {

		// must pass a txnid, order id, and an amount for the transaciton to
		// be allowed.
		if (!isset($params['litleTxnId']) || empty($params['litleTxnId'])) {
			throw new Exception\MissingRequestParameterException('litleTxnId');
		}
		if (!isset($params['id']) || empty($params['id'])) {
			throw new Exception\MissingRequestParameterException('id (alias for: orderId]');
		}
		if (!isset($params['amount']) || empty($params['amount'])) {
			throw new Exception\MissingRequestParameterException('amount');
		}

		// sandbox mode we will append the 000 to the txn id so that we will
		// recieve an approved response.
		// 
		// @todo better method for testing more response codes!
		if ($this->mode == 'sandbox') {
			$params['litleTxnId'] = substr_replace((string) $params['litleTxnId'], '000', -3);
		}

		return $this->respond(
			$this->litle_sdk->creditRequest($params)
		);
	}

	/**
	 * Respond to a Capture Transaction
	 *
	 * @todo documentation here
	 */
	public function respond($raw_response) {
		return new Response\CreditResponse($raw_response, $this->mode);
	}

}