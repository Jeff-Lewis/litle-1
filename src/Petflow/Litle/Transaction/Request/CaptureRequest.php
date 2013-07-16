<?php namespace Petflow\Litle\Transaction\Request;
	
use Petflow\Litle\ResponseCode;
use Petflow\Litle\Exception;
use Petflow\Litle\Transaction\Response;

/**
 * Capture Transaction
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright Petflow.com 2013
 */
class CaptureRequest extends TransactionRequest {

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
	public function respond($raw_response) {
		return new Response\CaptureResponse($raw_response);
	}

}