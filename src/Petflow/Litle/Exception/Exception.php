<?php namespace Petflow\Litle\Exception;

use \Exception as Exception;

/**
 * Unknown Response Code Provided
 *
 * An exception to be thrown when an invalid response code is
 * encounterd. The message should contain the reason why and
 * the code encountered.
 */
class UnknownResponseCodeException extends Exception {} 

/**
 * Duplicate Transaction Detected
 *
 * An exception thrown when a duplicate transaction was
 * detected, used to determine if a transaction executed
 * contained the necessary flags to be a duplicate
 * detection.
 */
class DuplicateTransactionException extends Exception {

	/**
	 * @var Array The expected response data that was returned from transaction
	 */
	protected $response_data;

	/**
	 * Construction
	 *
	 * Creating this exception requires response data to be passed to it
	 * as so the end-user can still recieve information about the
	 * transaction.
	 * 
	 * @param [type] $response_data [description]
	 */
	public function __construct($response_data) {
		parent::__construct('Duplicate transaction detected, call get_response_data() for more information.');
		
		$this->response_data = $response_data;
	}

	/**
	 * Get Response Data
	 * 
	 * @return [type] [description]
	 */
	public function getResponseData() {
		return $this->response_data;
	}
}