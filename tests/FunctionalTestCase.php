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
	 * @var Array The parameters for connecting to Litle.
	 */
	protected static $params;

	/**
	 * SetUp for Tests
	 */
	public function setUp() {
		parent::setUp();


		if (!file_exists(__DIR__.'/config.json')) {
			die('Must provide config.json in the tests/ directory');
		} else {
			$file = file_get_contents(__DIR__.'/config.json');
			static::$params = json_decode($file, true);
		}
	}

	/**
	 * Get Params for Transactions
	 * 
	 * @return [type] [description]
	 */
	protected static function getParams() {
		return static::$params;
	}

	/**
	 * Get URL For Requests
	 * 
	 * @return [type] [description]
	 */
	protected static function getCfg() {
		if (static::$is_certification_environment && isset($params['certification_url']))  {
			return [
				'url' => $params['certification_url']
			];
		}

		return [];
	}
}