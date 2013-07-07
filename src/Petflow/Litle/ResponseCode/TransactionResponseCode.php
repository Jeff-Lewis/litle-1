<?php namespace Petflow\Litle\ResponseCode;

/**
 * Transaction Response Code
 *
 * @author Nate Krantz <nate@petflow.com>
 * @copyright Petflow 2013
 */
class TransactionResponseCode extends ResponseCode {

	/**
	 * Possible Transaction Response Codes
	 */
	protected static $codes = [

		// 0 - 200
		'000' => [
				'message'     	=> 'Approved',
				'type'			=> 'approved',
				'description' 	=> 'No action required.',
			],
		'010' => [
				'message'		=> 'Partially Approved',
				'type'			=> 'approved',
				'description'	=> 'The authorized amount is less than the requested amount.'
		],
		'100' => [
				'message'		=> 'Processing Network Unavailable',
				'type'			=> 'soft_decline',
				'description'	=> 'There is a problem with the card network. Contact the network for more information.'
			],
		'101' => [
				'message'		=> 'Issuer Unavailable',
				'type'			=> 'soft_decline',
				'description'	=> 'There is a problem with the issuer network. Please contact the issuing bank.'
			],
		'102' => [
				'message'		=> 'Re-submit Transaction',
				'type'			=> 'soft_decline',
				'description'	=> 'There is a temporary problem with your submission. Please re-submit the transaction.'
			],
		'110' => [
				'message'		=> 'Insufficient Funds',
				'type'			=> 'soft_decline',
				'description'	=> 'The card does not have enough funds to cover the transaction.'
			],
		'111' => [
				'message'		=> 'Authorization amount has already been depleted',
				'type'			=> 'hard_decline',
				'description'	=> 'The total amount of the original Authorization has been used.'
			],
		'120' => [
				'message'		=> 'Call Issuer',
				'type'			=> 'referral',
				'description'	=> 'There is an unspecified problem, contact the issuing bank.'
			],
		'121' => [
				'message'		=> 'Call AMEX',
				'type'			=> 'referral',
				'description'	=> 'There is an unspecified problem; contact AMEX.'
			],
		'122' => [
				'message'		=> 'Call Diners Club',
				'type'			=> 'referral',
				'description'	=> 'There is an unspecified problem; contact Diners Club.'
			],
		'123' => [
				'message'		=> 'Call Discover',
				'type'			=> 'referral',
				'description'	=> 'There is an unspecified problem; contact Discover.'
			],
		'124' => [
				'message'		=> 'Call JBS',
				'type'			=> 'referral',
				'description'	=> 'There is an unspecified problem; contact JBS.'
			],
		'125' => [
				'message'		=> 'Call Visa/MasterCard',
				'type'			=> 'referral',
				'description'	=> 'There is an unspecified problem; contact Visa or MasterCard.'
			],
		'126' => [
				'message'		=> 'Call Issuer - Update Cardholder',
				'type'			=> 'referral',
				'description'	=> 'Some data is out of date; contact the Data issuer to update this information.'
			],
		'127' => [
				'message'		=> 'Exceeds Approval Amount Limit',
				'type'			=> 'hard_decline',
				'description'	=> 'This transaction exceeds the Decline daily approval limit for the card.'
			],
		'130' => [
				'message'		=> 'Call Indicated Number',
				'type'			=> 'referral',
				'description'	=> 'There is an unspecified problem; contact the phone number provided.'
			],
		'140' => [
				'message'		=> 'Update Cardholder Data',
				'type'			=> 'referral',
				'description'	=> 'Cardholder data is incorrect; contact the issuing bank.'
			],
		'191' => [
				'message'		=> 'The merchant is not registered in the update program.',
				'type'			=> 'n/a',
				'description'	=> 'This is an Account Updater response indicating a set-up problem that must be resolved prior to submitting another request file. Escalate this to your Litle Customer Experience Manager.'
			],
		'192' => [
				'message'		=> 'Merchant not certified/enabled for IIAS',
				'type'			=> 'hard_decline',
				'description'	=> 'Your organization is not certified IIAS Decline or enabled for IIAS/FSA transactions.'
			],

		// 300	
		'301' => [
				'message'		=> 'Invalid Account Number',
				'type'			=> 'hard_decline',
				'description'	=> 'The account number is not valid; contact the cardholder to confirm information or inquire about another form of payment.'
			],
		'302' => [
				'message'		=> 'Account Number Does Not Match Payment Type',
				'type'			=> 'hard_decline',
				'description'	=> 'The payment type was selected Payment Type as one card type (e.g. Visa), but the card number indicates a different card type (e.g. MasterCard).'
			],
		'303' => [
				'message'		=> 'Pick Up Card',
				'type'			=> 'hard_decline',
				'description'	=> 'This is a card present response, but in a card not present environment. Do not process the transaction and contact the issuing bank.'
			],
		'304' => [
				'message'		=> 'Lost/Stolen Card',
				'type'			=> 'hard_decline',
				'description'	=> 'The card has been designated as lost or stolen; contact the issuing bank.'
			],
		'305' => [
				'message'		=> 'Expired Card',
				'type'			=> 'hard_decline',
				'description'	=> 'The card is expired.'
			],
		'306' => [
				'message'		=> 'Authorization has expired; no need to reverse',
				'type'			=> 'hard_decline',
				'description'	=> 'The original Authorization is no longer valid, because it has expired. You can not perform an Authorization Reversal for an expired Authorization.'
			],
		'307' => [
				'message'		=> 'Restricted Card',
				'type'			=> 'hard_decline',
				'description'	=> 'The card has a restriction preventing approval for this transaction. Please contact the issuing bank for a specific reason. You may also receive this code if the transaction erwas declined due to Prior Fraud Advice Filtering and you are using a schema version V8.10 or older.'
			],
		'308' => [
				'message'		=> 'Restricted Card - Chargeback',
				'type'			=> 'hard_decline',
				'description'	=> 'This transaction is being declined due the operation of the Litle Prior Chargeback Card Filtering Service or the card has a restriction preventing approval if there are any chargebacks against it.'
			],
		'309' => [
				'message'		=> 'Restricted Card - Prepaid Filtering Service',
				'type'			=> 'hard_decline',
				'description'	=> 'Card This transaction is being declined due the operation of the Litle Prepaid Card Filtering service.'
			],
		'310' => [
				'message'		=> 'Invalid track data',
				'type'			=> 'hard_decline',
				'description'	=> 'The track data is not valid.'
			],
		'311' => [
				'message'		=> 'Deposit is already referenced by a chargeback',
				'type'			=> 'hard_decline',
				'description'	=> 'The deposit is already referenced by a chargeback; therefore, a refund cannot be processed against the original transaction.'
			],
		'312' => [
				'message'		=> 'Restricted Card - International Card Filtering Service ',
				'type'			=> 'hard_decline',
				'description'	=> 'This transaction is being declined due the operation of the Litle International Card Filtering Service.'
			],
		'315' => [
				'message'		=> 'Restricted Card - Auth Fraud Velocity Filtering Service',
				'type'			=> 'hard_decline',
				'description'	=> 'This transaction is being declined due the operation of the Litle Auth Fraud Velocity Filtering Service.'
			],
		'316' => [
				'message'		=> 'Automatic Refund Already Issued',
				'type'			=> 'hard_decline',
				'description'	=> 'This refund transaction is a duplicate for one already processed automatically by the Litle Fraud Chargeback Prevention Service (FCPS).'
			],
		'318' => [
				'message'		=> 'Restricted Card - Auth Fraud Advice Filtering Service',
				'type'			=> 'hard_decline',
				'description'	=> 'This transaction is being declined due the operation of the Litle Auth Fraud Advice Filtering Service.'
			],
		'320' => [
				'message'		=> 'Invalid Expiration Date ',
				'type'			=> 'hard_decline',
				'description'	=> 'The expiration date is invalid.'
			],
		'321' => [
				'message'		=> 'Invalid Merchant',
				'type'			=> 'hard_decline',
				'description'	=> 'The card is not allowed to make purchases from this merchant (e.g. a Travel only card trying to purchase electronics).'
			],
		'323' => [
				'message'		=> 'No such issuer',
				'type'			=> 'hard_decline',
				'description'	=> 'The card number references an issuer that does not exist. Do not process the transaction.'
			],
		'324' => [
				'message'		=> 'Invalid pin',
				'type'			=> 'hard_decline',
				'description'	=> 'The pin provided is invalid'
			],
		'325' => [
				'message'		=> 'Transaction not allowed at terminal',
				'type'			=> 'hard_decline',
				'description'	=> 'The transaction is not permitted; contact the issuing bank.'
			],
		'326' => [
				'message'		=> 'Exceeds number of PIN entries',
				'type'			=> 'hard_decline',
				'description'	=> '(Referring to a debit card) The incorrect PIN has been entered excessively and the card is locked.'
			],
		'327' => [
				'message'		=> 'Cardholder transaction not permitted',
				'type'			=> 'hard_decline',
				'description'	=> 'Merchant does not allow that card type or specific transaction.'
			],
		'328' => [
				'message'		=> 'Cardholder requested that recurring or installment payment be stopped',
				'type'			=> 'hard_decline',
				'description'	=> 'Recurring/Installment Payments no longer accepted by the card issuing bank.'
			],
		'330' => [
				'message'		=> 'Invalid Payment Type',
				'type'			=> 'hard_decline',
				'description'	=> 'This payment type is not accepted by the issuer.'
			],
		'335' => [
				'message'		=> 'This method of payment does not support authorization reversals',
				'type'			=> 'hard_decline',
				'description'	=> 'You can not perform an Authorization Reversal transaction for this payment type.'
			],
		'336' => [
				'message'		=> 'Reversal amount does not match Authorization amount.',
				'type'			=> 'hard_decline',
				'description'	=> 'For a merchant initiated reversal against an American Express authorization, the reversal amount must match the authorization amount exactly.'
			],
		'340' => [
				'message'		=> 'Invalid amount',
				'type'			=> 'hard_decline',
				'description'	=> 'The transaction amount is invalid (too high or too low). For example, less than 0 for an authorization, or less than .01 for other payment types.'
			],
		'346' => [
				'message'		=> 'Invalid billing descriptor prefix',
				'type'			=> 'hard_decline',
				'description'	=> 'The billing descriptor prefix submitted is not valid.'
			],
		'347' => [
				'message'		=> 'Invalid billing descriptor ',
				'type'			=> 'hard_decline',
				'description'	=> 'The billing descriptor is not valid because you are not authorized to send transactions with custom billing fields.'
			],
		'316' => [
				'message'		=> '',
				'type'			=> '',
				'description'	=> ''
			],
	];
}