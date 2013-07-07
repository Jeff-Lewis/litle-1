<?php namespace Petflow\Litle\ResponseCode;

use Petflow\Litle\Exception;

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
	 * 	[
	 * 		code => [
	 * 	 		message		=> <string> <optional>
	 * 	 		description => <string>
	 * 		]
	 * 	]
	 * @return array Array containing codes and additional information about each code.
	 */
	protected static $codes;

	/**
	 * Magic Get
	 *
	 * Anytime this class has an unidentified static method applied to
	 * it we are going to assume that the caller is looking for a
	 * response code.
	 * 
	 * @param  integer $code The code being requested
	 * @return array       	 An array containing details or an empty array if not found.
	 */
	public static function code($code) {
		if (array_key_exists($code, static::$codes)) {
			return static::$codes[$code];
		} else {
			throw new Exception\InvalidResponseCodeException('Unknown response code provided: '.$code);
		}
	}

}