<?php

/**
 * Base Test Case
 */
class UnitTestCase extends PHPUnit_Framework_TestCase {

	/**
	 * Tear Down
	 */
	public function tearDown() {
		Mockery::close();
	}

	/**
	 * Make Authoirzation XML Response
	 */
	public function makeAuthorizationXMLResponse($attributes, $nodes) {
		return $this->makeXmlResponse(
			'<authorizationResponse '.$this->buildXMLAttributes($attributes).' reportGroup="default">'.
				$this->buildXMLNodes($nodes).
			'</authorizationResponse>'
		);
	}

	/**
	 * Make Sale XML Response
	 */
	public function makeSaleXmlResponse($attributes, $nodes) {
		return $this->makeXmlResponse(
			'<saleResponse '.$this->buildXMLAttributes($attributes).' reportGroup="default">'.
				$this->buildXMLNodes($nodes).
			'</saleResponse>'
		);
	}

	/**
	 * Make an XML Responses
	 */
	public function makeXmlResponse($content) {
		$dom = new DOMDocument();
		$dom->loadXML(
			trim('
				<litleOnlineResponse version="8.15" xmlns="http://www.litle.com/schema" response="0" message="Valid Format">
					'.$content.'
				</litleOnlineResponse>
			')
		);
		return $dom;
	}

	/**
	 * Build XML Attributes
	 */
	private function buildXMLAttributes($attributes) {
		$xml_attributes = '';

		foreach ($attributes as $attribute => $value) {
			$xml_attributes .= " $attribute='$value' ";
		}

		return $xml_attributes;
	}

	/**
	 * Build XML Nodes
	 */
	private function buildXMLNodes($nodes) {
		$xml_nodes = '';

		foreach ($nodes as $node => $value) {
			$xml_nodes .= "<$node>$value</$node>\n";
		}

		return $xml_nodes;
	}	
}