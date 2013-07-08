<?php

/**
 * Functional Test Case
 */
class FunctionalTestCase extends PHPUnit_Framework_TestCase {

	/**
	 * @var boolean Turn this on to true when we want to test for certs
	 */
	protected static $is_certification_environment = false;

	/**
	 * Get Params for Transactions
	 * 
	 * @return [type] [description]
	 */
	protected static function getParams() {
		return [
			'username' => 'PETFLOW',
			'password' => '',
			'merchant' => '120400'
		];
	}

	/**
	 * Get URL For Requests
	 * 
	 * @return [type] [description]
	 */
	protected static function getCfg() {
		if (static::$is_certification_environment)  {
			return [
				'url' => 'place certification url here'
			];
		}

		return [];
	}
}