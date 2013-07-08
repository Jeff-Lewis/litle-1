<?php namespace Petflow\Litle\Transaction;

use Petflow\Litle\Exception\DuplicateTransactionException as DuplicateTransactionException;

/**
 * Perform a Sale Transaction
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow 2013
 */
class SaleTransaction extends Transaction {	

	/**
	 * @var [type] LitleOnlineRequest
	 */
	protected $transaction;

	/**
	 * [__construct description]
	 * @param [type] $configuration [description]
	 */
	public function __construct($params = [], $overrides = [], $litle_online_request=null) {
		$provided_cfg = [];
		
		if (isset($params['username']) && isset($params['password']) && isset($params['merchent'])) {
			$provided_cfg = [
				'user' 					=> $params['username'],
				'password' 				=> $params['password'],
				'currency_merchant_map' => ['DEFAULT' => $params['merchent']]
			];
		} 

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

		// litle dependency injection
		if (is_null($litle_online_request)) {
			$this->transaction = new \LitleOnlineRequest(static::$config);
		} else {
			$this->transaction = $litle_online_request;
		}
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
		return $this->respond(
			$this->transaction->saleRequest($params)
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
			'duplicate'             => \XMLParser::getAttribute($response, 'saleResponse', 'duplicate'),
			'litle_transaction_id'  => \XMLParser::getNode($response, 'litleTxnId'),
			'response_time'			=> 
				(new \DateTime(\XMLParser::getNode($response, 'responseTime')))
					->format('Y-m-d H:i:s'),
		];

		if ($parsed['duplicate']) {
			throw new DuplicateTransactionException($parsed);
		}
			
		return $parsed;
	}

}