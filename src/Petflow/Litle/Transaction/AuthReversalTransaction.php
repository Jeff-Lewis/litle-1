<?php namespace Petflow\Litle\Transaction;

class AuthReversalTransaction extends Transaction {

	public function make($params) {
		return $this->respond(
			$this->litle_sdk->authReversalRequest($params)
		);
	}

	public function respond($response) {
		return $response;
	}
	
}