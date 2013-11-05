<?php

use \Exception as Exception;
	
/**
 * Missing Request Parameter
 *
 * An exception to be thrown when a missing request parameter
 * is detected, useful for setting requirements when sending
 * any requests to the Litle service.
 */
class MissingRequestParameterException extends Exception {

	/**
	 * Contruct
	 *
	 * Pass in the parameter that was not found and the
	 */
	public function __construct($parameter, $msg='') {
		parent::__construct('Request parameter: '.$parameter.' not found.');

		$this->custom_msg = $msg;
	}

	/**
	 * Custom Message
	 *
	 * Get the custom message
	 */
	public function getCustomMessage() {
		return $this->custom_msg;
	}
}

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
	 * @var \TransactionResponse Contains the response that the duplicate transaction was detected.
	 */
	protected $response;

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
	 * @param \Petflow\Litle\Transaction\TransactionResponse
	 * @param Array
	 */
	public function __construct(\Petflow\Litle\Transaction\TransactionResponse $response, $response_data) {
		parent::__construct('Duplicate transaction detected, call get_response_data() for more information.');
		
		$this->response = $response;
		$this->response_data = $response_data;
	}

	/**
	 * Get Response
	 * 
	 * @return \Petflow\Litle\Transaction\TransactionResponse
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * Get Response Data
	 * 
	 * @return Array
	 */
	public function getResponseData() {
		return $this->response_data;
	}
}