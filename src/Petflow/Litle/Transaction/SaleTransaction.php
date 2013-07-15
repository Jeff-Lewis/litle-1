<?php namespace Petflow\Litle\Transaction;

use Petflow\Litle\ResponseCode;
use Petflow\Litle\Exception;

/**
 * Perform a Sale Transaction
 *
 * A class to perform sale transactions against the litle service, currently
 * using the litle_sdk_for_php library as the communication platform
 * between the client here and the service.
 *
 * For more information on the parameters we can send to the service, the 
 * request/response, and other litle specific questions you can consult
 * the documentation below.
 *
 * https://github.com/LitleCo/litle-xml/blob/master/reference_guides/Litle_XML_Reference_Guide_XML8.17_V2.32.pdf
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow 2013
 */
class SaleTransaction extends TransactionRequest {	

	/**
	 * Perform a Sale Transaction
	 *
	 * Will attempt to perform a sale transaction with the transaction
	 * object set up in by the constructor. For more information on 
	 * the parameters that can be sent, please refer to the documentation
	 * for SaleTransactions provided in the class documentation block.
	 *
	 * @param  array $params The parameters being sent to litle a the transaction.
	 * @return array 		 A parsed response containing the variables produced by the response.
	 */
	public function make($params) {
		return $this->respond(
			$this->litle_sdk->saleRequest($params)
		);
	}

	/**
	 * Respond to a Sale Transaction
	 *
	 * This will attempt to parse a sale transaction, using the XML response
	 * to grab fields using the \XMLParser.
	 *
	 * @todo Add check / exception if response is NOT XML.
	 * @todo XMLParser throws exceptions, we could catch and throw our own.
	 * 
	 * @param  array $response An XML node containing the response.
	 * @return array 		   An array of key/value pairs parsed from the XML response.
	 */
	public function respond($response) {
		$parsed = [
			'response' 				=> \XMLParser::getNode($response, 'response'),
			'message' 				=> \XMLParser::getNode($response, 'message'),
			'auth_code'				=> \XMLParser::getNode($response, 'authCode'),
			'avs_result'			=> \XMLParser::getNode($response, 'avsResult'),
			'cv_result'				=> \XMLParser::getNode($response, 'cardValidationResult'),
			'auth_result'			=> \XMLParser::getNode($response, 'authenticationResult'),
			'duplicate'             => \XMLParser::getAttribute($response, 'saleResponse', 'duplicate'),
			'litle_transaction_id'  => \XMLParser::getNode($response, 'litleTxnId'),
			'response_time'			=> 
				(new \DateTime(\XMLParser::getNode($response, 'responseTime')))
					->format('Y-m-d H:i:s'),
		];

		// make the detailed response pulling in additional information from
		// the transaction respones code.
		try {
			$parsed['detailed_response'] = ResponseCode\TransactionResponseCode::code($parsed['response']);

		} catch (UnknownResponseCodeException $e) {
			$parsed['detailed_response'] = $e->getMessage();
		}
	}

}