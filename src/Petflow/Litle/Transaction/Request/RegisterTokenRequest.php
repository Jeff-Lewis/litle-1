<?php namespace Petflow\Litle\Transaction\Request;

use Petflow\Litle\ResponseCode;
use Petflow\Litle\Transaction\Response;

/**
 * Register Token Transaction
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow.com 2013
 */
class RegisterTokenRequest extends TransactionRequest {

	/**
	 * Make Request
	 */
	public function make($params) {

		if (!isset($params['accountNumber']) && !isset($params['paypageRegistrationId'])) {
			throw new \MissingRequestParameterException('accountNumber OR paypageRegistrationId');
		}
		if (!isset($params['orderId']) || empty($params['orderId'])) {
			throw new \MissingRequestParameterException('orderId');
		}
		if (isset($params['accountNumber']) && isset($params['paypageRegistrationId'])) {
			throw new \Exception('Both accountNumber and paypageRegistrationId detected! Please provide only one.');
		}

		return $this->respond(
			$this->litle_sdk->registerTokenRequest($params)
		);
	}

	/**
	 * Response to a Request Made
	 */
	public function respond($raw_response) {
		return new Response\RegisterTokenResponse($raw_response);
	}

}