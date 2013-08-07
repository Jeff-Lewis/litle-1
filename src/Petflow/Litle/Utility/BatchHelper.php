<?php namespace Petflow\Litle\Utility;

/**
 * Batch Request / Response Helper
 */
class BatchHelper {
		
	/**
	 * Batch Request XML
	 */
	public static function batch_request_xml($sess_id, $batch_id, $username, $password, array $batch_xml_array, $add='') {
		$str = '<litleRequest version="8.17" xmlns="http://www.litle.com/schema" id="'.$sess_id.'" numBatchRequests="1">'.
				'<authentication>'.
					'<username>'.$username.'</username>'.
					'<password>'.$password.'</password>'.
				'</authentication>'.
				'<batchRequest id="'.$batch_id.'" '.$add.'>'.
					.explode($batch_xml_array).
				'</batchRequest>'.
			'</litleRequest>';

		return simplexml_load_string($str);
	}
}