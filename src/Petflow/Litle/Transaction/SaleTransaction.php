<?php namespace Petflow\Litle\Transaction;

/**
 * Perform a Sale Transaction
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow 2013
 */
class SaleTransaction extends Transaction {	

	/**
	 * [__construct description]
	 * @param [type] $configuration [description]
	 */
	public function __construct($user, $password, $merchant, $overrides = []) {
		$provided_cfg = [
			'user' 					=> $user,
			'password' 				=> $password,
			'currency_merchant_map' => ['DEFAULT' => $merchant]
		];

		// merge what was provided into the defaults, overrwriting what is
		// necessary.
		static::$config = array_merge(
			[
				'url'			=> static::DEFAULT_CFG_URL,
				'proxy' 		=> static::DEFAULT_CFG_PROXY,
				'timeout' 		=> static::DEFAULT_CFG_TIMEOUT,
				'reportGroup' 	=> static::DEFAULT_CFG_REPORT_GROUP
			],
			$provided_cfg
		);
	}

	/**
	 * Perform a Sale Transaction
	 *
	 * @todo  lets add some better error handling
	 *
	 * @throws Exception If something goes wrong in the process
	 * 
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function make($params) {
		$transaction = new \LitleOnlineRequest(static::$config);

		return $this->respond(
			$transaction->saleRequest($params)
		);
	}

	/**
	 * Respond to a Sale Transaction
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
			'auth_result'			=> \XMLParser::getNode($response, 'authenticationResult'),

			'litle_transaction_id'  => \XMLParser::getNode($response, 'litleTxnId'),
			'timestamp'				=> strtotime(
				\XMLParser::getNode($response, 'responseTime')
			)
		];
			
		return $parsed;
	}

}