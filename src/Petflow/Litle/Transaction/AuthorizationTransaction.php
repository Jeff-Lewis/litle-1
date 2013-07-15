<?php namespace Petflow\Litle\Transaction;

use Petflow\Litle\Exception;
use Petflow\Litle\ResponseCode;

/**
 * Authoirzation Transaction
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow.com 2013
 */
class AuthorizationTransaction extends TransactionRequest {

	/**
	 * Make an Authorization Transaction
	 *
	 * Perform an authorization request to litle to authorize the given
	 * request. An authoirzation request will authorize the card and then
	 * authorize the funds on the card being passed. It will NOT charge
	 * the card and if anything goes awry the authorization transaction
	 * must be reversed.
	 *
	 * Note: if you provide amount of '000' the reuest will be an 
	 * AVS only request, only performing the AVS validation.
	 */
	public function make($params) {

		// @todo add checks for customer info / card info

		// making an authorization transaction requires an order id
		// which is used to assoicate back to the merchants system
		if (!isset($params['orderId'])) {
			throw new Exception\MissingRequestParameterException('orderId');
		}

		return $this->respond(
			$this->litle_sdk->authorizationRequest($params)
		);
	}

	/**
	 * Respond to an Authroization Transaction
	 *
	 * After the transaction is made, this function will create the
	 * response to be sent back to the client caller. The response is 
	 * expected to have detailed information about the avs response, 
	 * cv response, and transaction response when available.
	 */
	public function respond($response) {
		$parsed = [
			'response' 				=> \XMLParser::getNode($response, 'response'),
			'message' 				=> \XMLParser::getNode($response, 'message'),
			'auth_code'				=> \XMLParser::getNode($response, 'authCode'),
			'avs_result'			=> \XMLParser::getNode($response, 'avsResult'),
			'cv_result'				=> \XMLParser::getNode($response, 'cardValidationResult'),
			'litle_transaction_id'  => \XMLParser::getNode($response, 'litleTxnId'),
			'response_time'			=> 
				(new \DateTime(\XMLParser::getNode($response, 'responseTime')))
					->format('Y-m-d H:i:s'),
		];

		// make the detailed response pulling in additional information from
		// the transaction respones code.
		try {
			$parsed['avs_response'] = ResponseCode\AVSResponseCode::code($parsed['avs_result']);
			$parsed['cv_response'] = ResponseCode\CVResponseCode::code($parsed['cv_result']);
			$parsed['transaction_response'] = ResponseCode\TransactionResponseCode::code($parsed['response']);

		} catch (UnknownResponseCodeException $e) {
			$parsed['unknown_response'] = $e->getMessage();

			if (!isset($parsed['avs_response'])) { $parsed['avs_response'] = null; }
			if (!isset($parsed['cv_response']))  { $parsed['cv_response'] = null; }
			if (!isset($parsed['transaction_response'])) { $parsed['transaction_response'] = null;}
		}

		return $parsed;
	}

}