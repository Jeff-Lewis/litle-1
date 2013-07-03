<?php namesapce Petflow\Litle\ResponseCode;

/**
 * CV Response Codes
 *
 * @author Nate Krantz <nkrantz@petflow.com>
 * @copyright 2013
 */
class CVResponseCode extends ResponseCode {

	/**
	 * Possible CV Response Codes
	 */
	protected static $codes = [
		'M' => [
				'description' => 'Match'
			],
		'N' => [
				'description' => 'No Match'
			],
		'P' => [
				'description' => 'Not Processed'
			],
		'S' => [
				'description' => 'CVV2/CVC2/CID should be on the card, but the merchant has indicated CVV2/CVC2/CID is not present'
			],
		'U' => [
				'description' => 'Issuer is not certified for CVV2/CVC2/CID processing'
			],
		''	=> [
				'description' => 'Check was not done for an unspecified reason'
			],
	];
}