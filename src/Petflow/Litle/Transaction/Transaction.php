<?php namespace Petflow\Litle\Transaction;

/**
 * Transaction Interface
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright Petflow 2013
 */
abstract class Transaction {

	/**
	 * Constants for our defualt configuration
	 */
	const DEFAULT_CFG_URL			= 'https://www.testlitle.com/sandbox/communicator/online';
	const DEFAULT_CFG_PROXY			= '';
	const DEFAULT_CFG_TIMEOUT		= '30';
	const DEFAULT_CFG_REPORT_GROUP	= 'Default Report Group';

	/**
	 * @var [type]
	 */
	protected static $config;

	/**
	 * Make a Transaction
	 * 
	 * @param  [type] $params [description]
	 * @return [type]         [description]
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