<?php namespace Petflow\Litle\Transaction\Request;

/**
 * Transaction Interface
 *
 * An interface that defines the process of making a request to 
 * the Litle online service. Transactions are perfor
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright Petflow 2013
 */
abstract class TransactionRequest {

	/**
	 * The URL is where the service makes the request, by default, we use the sandbox.
	 */
	const DEFAULT_CFG_URL = 'https://www.testlitle.com/sandbox/communicator/online';

	/**
	 * Consult Litle documentation.
	 */
	const DEFAULT_CFG_PROXY	= '';

	/**
	 * The default time out, set to 60 seconds as recommended by the Litle user guide
	 * as to avoid the possibility of creating undetected duplicate transactions.
	 */
	const DEFAULT_CFG_TIMEOUT = '60';

	/**
	 * Consult litle documentation.
	 */
	const DEFAULT_CFG_REPORT_GROUP = 'Default Report Group';

	/**
	 * @var array Contains the configuration opens for opening a request.
	 */
	protected static $config;

	/**
	 * @var \LitleOnlineRequest LitleOnlineRequest object for making transactions
	 */
	protected $litle;

	/**
	 * Current Mode for Litle
	 * @var [type]
	 */
	protected $mode;

	/**
	 * Construct
	 *
	 * When constructing a sale transaction, each transaction must
	 * accept params for the username, pass, and merchant, optional
	 * overrides for other Litle configuration options, and an optional
	 * dependency injection if we want to override the litle_online_request
	 * class.
	 *
	 * Consult litle documentation for more information on the parameters
	 * and overrides you can send, found in the class block documentation
	 * at the begining of this file.
	 *
	 * @todo This should be moved to the abstract class!
	 *
	 * @param array $params Expecting the 'username', 'password', and 'merchant' for litle.
	 * @param array $overrides Can override options such as 'url', 'proxy', 'timeout', and 'reportGroup'
	 */
	public function __construct($params, $overrides = [], $litle_online_request=null) {
		$provided_cfg = [];

		if (isset($params['username']) && isset($params['password']) && isset($params['merchant'])) {
			$provided_cfg = [
				'user' 					=> $params['username'],
				'password' 				=> $params['password'],
				'currency_merchant_map' => ['DEFAULT' => $params['merchant']],
				'url'                   => (isset($params['url'])) ? $params['url'] : self::DEFAULT_CFG_URL
			];
		} 

		// merge what was provided into the defaults, overrwriting what is
		// necessary.
		static::$config = array_merge(
			[
				'proxy' 		=> static::DEFAULT_CFG_PROXY,
				'timeout' 		=> static::DEFAULT_CFG_TIMEOUT,
				'reportGroup' 	=> static::DEFAULT_CFG_REPORT_GROUP
			],
			$provided_cfg
		);

		// set the mode
		if (isset($params['mode'])) {
			$this->mode = $params['mode'];
			
		} else {
			$this->mode = 'sandbox';
		}

		// var_export(static::$config);
		// die();

		// litle dependency injection
		if (is_null($litle_online_request)) {
			$this->litle_sdk = new \LitleOnlineRequest(static::$config);
		} else {
			$this->litle_sdk = $litle_online_request;
		}
	}

	/**
	 * Make a Transaction
	 *
	 * With this function a request to the litle service will be made via 
	 * the litle_sdk_for_php libraries. Each transaction type must implement
	 * this function so that we can take care of oddities for the specific
	 * transaction types. Since this function is a wrapper for the sdk, it
	 * is expected to be pretty lightweight.
	 *
	 * On an error, it will throw exceptions originating from the
	 * litle_sdk library. In the future, we may decide to write our own
	 * version of the sdk library so this interface will still be
	 * appliciable as such.
	 *
	 * After a response is recieved and no exceptions are raised, the
	 * function will then return a response and is depended on the
	 * respond() function of this class.
	 *
	 * @param  array $params The parameters to be sent to the request service.
	 */
	abstract public function make($params);

	/**
	 * Respond
	 *
	 * With this function respond to a request that was performed by
	 * the make() function. Typically, this will take the response and
	 * ensure that it is valid XML, parse out the parameters that the
	 * current transaction type nedds, and then feed back an array
	 * containing the response.
	 * 
	 * @param  XMLDocumemt $response An XMLDocument containing the response.
	 */
	abstract public function respond($response);

	/**
	 * Translate Params
	 *
	 * This function will accept an input array of parameters and convert
	 * its keys to camelCase from snake_case. Note that this function will
	 * only perform the translation one element deep, it will not check
	 * any multidimensional arrays.
	 *
	 * @param Array $params The input array to translate into camelCase
	 */
	public function translate(array $params) {
		$out = [];

		foreach ($params as $key => $val) {
			$new_key = ucwords(str_replace('_', ' ', $key));
			$out[lcfirst(str_replace(' ', '', $new_key))] = $val;
		}	

		return $out;
	}

}