<?php namespace Petflow\Litle\Transaction\Batch;
 
/**
 * Batch Token Registration
 *
 * @author Nate Krantz <nate@petflow.com>
 */
class TokenRegistration {

	/**
	 * URL
	 * @var [type]
	 */
	protected static $url;

	/**
	 * User
	 * @var [type]
	 */
	protected static $user;

	/**
	 * Password
	 * @var [type]
	 */
	protected static $pass;

	/**
	 * Merchant
	 * @var [type]
	 */
	protected static $merchant;

	/**
	 * Construction
	 */
	public function __construct($config) {
		self::$url      = $config['url'];
		self::$user     = $config['username'];
		self::$pass     = $config['password'];
		self::$merchant = $config['merchant'];
	}

	/**
	 * Send a Batch Token Registration
	 */
	public function send(array $payment_options) {
		$batch_id = strtotime('now');
		$requests = [];

		foreach ($payment_options as $option) {
			$requests[] = self::register_token_request_xml($option['payment_option_id'], $option['card']);
		}

		$batch    = self::register_token_batch_xml_wrapper($batch_id, $requests);
		$response = self::litle_request($batch);

		$xml = simplexml_load_string($response);

		return $xml;
	}

	/**
	 * Litle Request
	 */
	private static function litle_request($content) {
		$xml = new \SimpleXMLElement($content);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
		curl_setopt($ch, CURLOPT_URL, self::$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml->asXML());
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);

		$output       = curl_exec($ch);
		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (! $output){
			throw new Exception (curl_error($ch));
		}
		else {
			curl_close($ch);
			return $output;
		}
	}

	/**
	 * Register Token Request XML
	 */
	private static function register_token_request_xml($id, $account_number) {
		return 
			'      <registerTokenRequest id="'.$id.'" reportGroup="default">'."\n".
			'         <orderId>'.$id.'</orderId>'."\n".
		    '         <accountNumber>'.$account_number.'</accountNumber>'."\n".
			'      </registerTokenRequest>'."\n";
	}

	/**
	 * Register Token Batch Wrapper
	 */
	private static function register_token_batch_xml_wrapper($batch_id, $registrations) {
		return  '<?xml version="1.0" encoding="utf-8" ?>'.
		        '<litleRequest version="8.17" xmlns="http://www.litle.com/schema" id="'.$batch_id.'" numBatchRequests="1">'."\n".
		        '   <authentication>'."\n".
		        '      <user>'.self::$user.'</user>'."\n".
		        '      <password>'.self::$pass.'</password>'."\n".
		        '   </authentication>'."\n".
		        '   <batchRequest id="'.$batch_id.'" numTokenRegistrations="'.count($registrations).'">'."\n"
		           		.implode("", $registrations).
		        '   </batchRequest>'."\n".
		        '</litleRequest>';
	}
}