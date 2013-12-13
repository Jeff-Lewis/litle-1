<?php namespace Petflow\Litle\ResponseCode;

/**
 * AVS Response Code
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright 2013
 */
class AVSResponseCode extends ResponseCode {

	/**
	 * Possible AVS Response Codes. 
	 */
	protected static $codes = [
		'01' => [
				'description' => '9-Digit zip and address match',
				'actual_code' => 'X'
			],
		'02' => [
				'description' => 'Postal code and address match',
				'actual_code' => 'Y'
			],
		'10' => [
				'description' => '5-Digit zip matches, address does not match',
				'actual_code' => 'Z'
			],
		'11' => [
				'description' => '9-Digit zip matches, address does not match',
				'actual_code' => 'W'
			],
		'12' => [
				'description' => 'Zip does not match, address matches',
				'actual_code' => 'A'
			],
		'13' => [
				'description' => 'Postal code does not match, address matches',
				'actual_code' => 'A'
			],
		'14' => [
				'description' => 'Postal code matches, address not verified',
				'actual_code' => 'A'
			],
		'20' => [
				'description' => 'Neither zip nor address match',
				'actual_code' => 'N'
			],
		'30' => [
				'description' => 'AVS service not supported by issuer',
				'actual_code' => 'S'
			],
		'31' => [
				'description' => 'AVS system not available',
				'actual_code' => 'U'
			],
		'32' => [
				'description' => 'Address unavailable',
				'actual_code' => 'U'
			],
		'33' => [
				'description' => 'General error',
				'actual_code' => 'U'
			],
		'34' => [
				'description' => 'AVS not performed',
				'actual_code' => 'R'
			],
		'40' => [
				'description' => 'Address failed Litle & Co. edit checks',
				'actual_code' => 'N'
			]
	];
	
}