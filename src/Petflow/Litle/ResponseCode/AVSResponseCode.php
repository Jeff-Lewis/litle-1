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
				'description' => '9-Digit zip and address match'
			],
		'02' => [
				'description' => 'Postal code and address match'
			],
		'10' => [
				'description' => '5-Digit zip matches, address does not match'
			],
		'11' => [
				'description' => '9-Digit zip matches, address does not match'
			],
		'12' => [
				'description' => 'Zip does not match, address matches'
			],
		'13' => [
				'description' => 'Postal code does not match, address matches'
			],
		'14' => [
				'description' => 'Postal code matches, address not verified'
			],
		'20' => [
				'description' => 'Neither zip nor address match'
			],
		'30' => [
				'description' => 'AVS service not supported by issuer'
			],
		'31' => [
				'description' => 'AVS system not available'
			],
		'32' => [
				'description' => 'Address unavailable'
			],
		'33' => [
				'description' => 'General error'
			],
		'34' => [
				'description' => 'AVS not performed'
			],
		'40' => [
				'description' => 'Address failed Litle & Co. edit checks'
			]
	];
	
}