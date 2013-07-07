<?php namespace Petflow\Litle\Exception;

use \Exception as Exception;

/**
 * Invalid Response Code Provided
 *
 * An exception to be thrown when an invalid response code is
 * encounterd. The message should contain the reason why and
 * the code encountered.
 */
class InvalidResponseCodeException extends Exception {} 