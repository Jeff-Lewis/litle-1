<?php namespace Petflow\Litle\ResponseCode;

use Petflow\Litle\Exception\UnknownResponseCodeException as UnknownResponseCodeException;

/**
 * Response Code
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 */
abstract class ResponseCode {

	/**
	 * Contains Response Codes
	 *
	 * This function is expected to return an array containing the
	 * response codes for the given response code class. The return
	 * value will have the codes as keys and following the format:
	 *
	 * @return array Array containing codes and additional information about each code.
	 */
	protected static $codes;

	/**
	 * Get a Code
	 *
	 * When this function is called, it will look in the static $codes
	 * array for the provided code. If it is found, it will be returned
	 * and otherwise it will throw an exception.
	 *
	 * @throws UnknownResponseCode If the response code isn't found.
	 * 
	 * @param  integer $code The code being requested
	 * @return array       	 An array containing details or an empty array if not found.
	 */
	public static function code($code) {
		if (array_key_exists($code, static::$codes)) {
			return static::$codes[$code];
		} else {
			throw new UnknownResponseCodeException('Unknown response code provided: '.$code);
		}
	}

}