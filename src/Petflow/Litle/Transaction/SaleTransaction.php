<?php namespace Petflow\Litle\Transaction;

/**
 * Perform a Sale Transaction
 *
 * @author Nate Krantz <nate@petflow.com>
 */
class SaleTransaction extends Transaction {	

	/**
	 * [__construct description]
	 * @param [type] $configuration [description]
	 */
	public function __construct($configuration = []) {

		// must provide a configuration, or a default configuration
		if (empty($configuration)) {

			// default configuration
			static::$config = [
				'user' 						=> 'PETFLOW',
				'password' 					=> '',
				'currency_merchant_map' 	=> [
					'DEFAULT' => 120400
				],
				'url'						=> 'https://www.testlitle.com/sandbox/communicator/online',
				'proxy' 					=> '',
				'timeout' 					=>  '65',
				'reportGroup' 				=> 'Default Report Group'
			];

		} else {

			// assign configuration from passed
			static::$config = $configuration;
		}
	}

	/**
	 * Perform a Sale Transaction
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
	 * ss
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
			'auth_result'			=> \XMLParser::getNode($repsonse, 'authenticationResult'),

			'litle_transaction_id'  => \XMLParser::getNode($response, 'litleTxnId'),
			'timestamp'				=> strtotime(
				\XMLParser::getNode($response, 'responseTime')
			)
		];
			
		return $parsed;
	}

}