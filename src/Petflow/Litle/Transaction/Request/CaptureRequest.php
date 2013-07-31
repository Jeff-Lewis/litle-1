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
		if (!isset($params['id'])) {
			throw new Exception\MissingRequestParameterException('id (alias for orderId)');
		}
		if (!isset($params['litleTxnId'])) {
			throw new Exception\MissingRequestParameterException('litleTxnId');
		}
		if (!isset($params['amount'])) {
			throw new Exception\MissingRequestParameterException('amount');
		}
		
		// sandbox we append the 000 so that it works
		if ($this->mode == 'sandbox') {
			$params['litleTxnId'] = substr_replace((string) $params['litleTxnId'], '000', -3);
		}

		return $this->respond(
			$this->litle_sdk->captureRequest($params)
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