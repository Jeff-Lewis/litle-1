<?php namespace Petflow\Litle\Transaction;

use Petflow\Litle\ResponseCode;

class AuthorizationTransaction extends Transaction {

	/**
	 * Make an Authorization Transaction
	 *
	 * Perform an authorization request to litle to authorize the given
	 * 
	 * 
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function make($params) {

		// if (!isset($params['orderId'])) {
		// 	throw new \Exception('Must')
		// }
		
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
	 * 
	 * @param  [type] $response [description]
	 * @return [type]           [description]
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