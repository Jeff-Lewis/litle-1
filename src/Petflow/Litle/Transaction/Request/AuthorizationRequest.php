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

		// we need an order id and an amount
		if (!isset($params['id'])) {
			throw new Exception\MissingRequestParameterException('id');
		}
		if (!isset($params['orderId'])) {
			throw new Exception\MissingRequestParameterException('orderId');
		}
		if (!isset($params['amount'])) {
			throw new Exception\MissingRequestParameterException('amount');
		}
		
		// @todo better handling of different response codes on functional
		// if ($this->mode == 'sandbox') { $params['amount'] = '1000'; } 

		// remove the comma!
		$params['amount'] = str_replace('.', '', (string) $params['amount']);
		$params['orderSource'] = self::DEFAULT_ORDER_SOURCE;

		// a card must be present to make an authorization request, unless
		// if in the future we want to do an AVS only authorization.
		$this->checkForCardRequirements($params);

		// the bill to address must be present so that AVS validation will
		// occur. we can override this behavior using the requireAddress = false
		// parameter
		$this->checkForBillToAddress($params);

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
		if (!isset($params['card']) && !isset($params['token'])) {
			throw new Exception\MissingRequestParameterException('card');

		} else if (isset($params['card'])) {
			if (!isset($params['card']['number'])) {
				throw new Exception\MissingRequestParameterException('card[number]');
			}
			if (!isset($params['card']['expDate'])) {
				throw new Exception\MissingRequestParameterException('card[expDate]');
			}
			if (!isset($params['card']['type'])) {
				throw new Exception\MissingRequestParameterException('card[type]');
			}
		} else if (isset($params['token'])) {
			if (!isset($params['token']['litleToken'])) {
				throw new Exception\MissingRequestParameterException('token[litleToken]');
			}
			if (!isset($params['token']['expDate'])) {
				throw new Exception\MissingRequestParameterException('token[expDate]');
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
				if (!isset($params['billToAddress']['name'])) {
					throw new Exception\MissingRequestParameterException('billToAddress[name]');
				}
				if (!isset($params['billToAddress']['zip'])) {
					throw new Exception\MissingRequestParameterException('billToAddress[zip]');
				}
			}
		}
	}

}