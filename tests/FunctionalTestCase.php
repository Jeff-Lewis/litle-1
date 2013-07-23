<?php

/**
 * Functional Test Case
 */
class FunctionalTestCase extends PHPUnit_Framework_TestCase {

	/**
	 * @var Array The parameters for connecting to Litle.
	 */
	protected static $params;

	/**
	 * SetUp for Tests
	 */
	public function setUp() {
		parent::setUp();

		// provide config json for running the tests
		if (!file_exists(__DIR__.'/config.json')) {
			die('Must provide config.json in the tests/ directory');
		} else {
			$file = file_get_contents(__DIR__.'/config.json');
			static::$params = json_decode($file, true);
		}
	}

	/**
	 * Get Params for Transactions
	 */
	protected static function getParams() {
		return static::$params;
	}

}