<?php namespace Petflow\Litle\Transaction;

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
class SaleTransaction extends Transaction {	

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
			$this->transaction->saleRequest($params)
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
	public function respond($raw_response) {
		$response = new TransactionResponse($raw_response);

		// additional data we might want to collect
		$response->message  		= \XMLParser::getNode($raw_response, 'message');
		$response->avs_result 		= \XMLParser::getNode($raw_response, 'avsResult');
		$response->cv_result 		= \XMLParser::getNode($raw_response, 'cardValidationResult'); 
		$response->auth_result 		= \XMLParser::getNode($raw_response, 'authenticationResult');

		return $response;
	}

}