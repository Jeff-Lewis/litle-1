<?php namespace Petflow\Litle\Transaction;

/**
 * Transaction Interface
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright Petflow 2013
 */
abstract class Transaction {

	/**
	 * The URL is where the service makes the request, by default, we use the sandbox.
	 */
	const DEFAULT_CFG_URL			= 'https://www.testlitle.com/sandbox/communicator/online';

	/**
	 * 
	 */
	const DEFAULT_CFG_PROXY			= '';

	/**
	 * The default time out, set to 60 seconds as recommended by the Litle user guide
	 * as to avoid the possibility of creating undetected duplicate transactions.
	 */
	const DEFAULT_CFG_TIMEOUT		= '60';

	/**
	 * 
	 */
	const DEFAULT_CFG_REPORT_GROUP	= 'Default Report Group';

	/**
	 * @var array Contains the configuration opens for opening a request.
	 */
	protected static $config;

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
	 * @throws InvalidArgumentException If the provided parameters aren't sufficient
	 * for a request to be made
	 *
	 * @throws Exception If any error occurs in the process of making the request.
	 * 
	 * @param  array $params The parameters to be sent to the request.
	 * @return array         An associative array representation of the response defined in the
	 *                       respond() function.
	 */
	abstract public function make($params);

	/**
	 * Respond
	 * 
	 * @param  [type] $response [description]
	 * @return [type]           [description]
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
	 * @throws Exception If the provided params argument is not an array
	 * 
	 * @param  array $params The input array to translate into camelCase
	 * @return array         An output array containing the same information
	 */
	public function translate($params) {
		$out = [];

		if (!is_array($params)) {
			throw new \Exception('Non-array provided to Petflow\Litle\Transaction::translate().');
		} else {
			foreach ($params as $key => $val) {
				$new_key = ucwords(str_replace('_', ' ', $key));
				$out[lcfirst(str_replace(' ', '', $new_key))] = $val;
			}	

			return $out;
		}
	}

}