<?php namespace Petflow\Litle\Transaction\Request;

use Petflow\Litle\Exception;
use Petflow\Litle\ResponseCode;
use Petflow\Litle\Transaction\Response;

/**
 * Authoirzation Transaction
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow.com 2013
 */
class AuthorizationRequest extends TransactionRequest {

	const DEFAULT_ORDER_SOURCE = 'ecommerce';

	/**
	 * Make an Authorization Transaction
	 *
	 * Perform an authorization request to litle to authorize the given
	 * request. An authoirzation request will authorize the card and then
	 * authorize the funds on the card being passed. It will NOT charge
	 * the card and if anything goes awry the authorization transaction
	 * must be reversed.
	 *
	 * Note: if you provide amount of '000' the reuest will be an 
	 * AVS only request, only performing the AVS validation.
	 *
	 * @param [type] [varname] [description]
	 */
	public function make($params) {

		// making an authorization transaction requires an order id
		// which is used to assoicate back to the merchants system
		if (!isset($params['orderId'])) {
			throw new Exception\MissingRequestParameterException('orderId');
		}

		// a card must be present to make an authorization request, unless
		// if in the future we want to do an AVS only authorization.
		$this->checkForCardRequirements($params);

		// the bill to address must be present so that AVS validation will
		// occur. we can override this behavior using the requireAddress = false
		// parameter
		$this->checkForBillToAddress($params);

		// hard coded for now
		$params['orderSource'] = self::DEFAULT_ORDER_SOURCE;

		if ($this->mode == 'sandbox') {
			$params['amount'] = '1000';
		}

		return $this->respond($this->litle_sdk->authorizationRequest($params));
	}

	/**
	 * Respond to an Authroization Transaction
	 *
	 * After the transaction is made, this function will create the
	 * response to be sent back to the client caller. The response is 
	 * expected to have detailed information about the avs response, 
	 * cv response, and transaction response when available.
	 *
	 * @param [type] [varname] [description]
	 */
	public function respond($raw_response) {
		return new Response\AuthorizationResponse($raw_response, $this->mode);
	}

	/**
	 * Check for Card
	 *
	 * The card must be present and at least the following fields must
	 * exist:
	 *
	 * 	- number
	 * 	- expDate
	 * 	- type
	 * 	
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function checkForCardRequirements($params) {
		if (!isset($params['card'])) {
			throw new Exception\MissingRequestParameterException('card');

		} else {
			if (!isset($params['card']['number'])) {
				throw new Exception\MissingRequestParameterException('card[number]');
			}
			if (!isset($params['card']['expDate'])) {
				throw new Exception\MissingRequestParameterException('card[expDate]');
			}
			if (!isset($params['card']['type'])) {
				throw new Exception\MissingRequestParameterException('card[type]');
			}
		}
	}

	/**
	 * Check for Billing Address
	 *
	 * The billing address must be present and at least the following fields must
	 * exist:
	 *
	 *    - zip
	 *    
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	private function checkForBillToAddress($params) {

		// we want to require address if
		if (!isset($params['requireAddress']) || $params['requireAddress']) {
			if (!isset($params['billToAddress'])) {
				throw new Exception\MissingRequestParameterException('billToAddress');

			} else {
				if (!isset($params['billToAddress']['zip'])) {
					throw new Exception\MissingRequestParameterException('billToAddress[zip]');
				}
			}
		}
	}

}