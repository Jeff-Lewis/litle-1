<?php

/**
 * Base Test Case
 */
class UnitTestCase extends PHPUnit_Framework_TestCase {

    /**
     * Tear Down
     */
    public function tearDown() {
        parent::tearDown();
        Mockery::close();
    }

	/**
	 * Make Authoirzation Response
	 */
	public static function makeAuthorizationXMLResponse($attributes, $nodes) {
		return static::makeXmlResponse(
			'<authorizationResponse '.static::buildXMLAttributes($attributes).' reportGroup="default">'.
				static::buildXMLNodes($nodes).
			'</authorizationResponse>'
		);
	}

	/**
	 * Make Auth Reversal Response
	 */
	public static function makeAuthReversalXMLResponse($attributes, $nodes) {
		return static::makeXmlResponse(
			'<authReversalResponse '.static::buildXMLAttributes($attributes).' reportGroup="default">'.
				static::buildXMLNodes($nodes).
			'</authReversalResponse>'
		);
	}

	/**
	 * Make Capture Response
	 */
	public static function makeCaptureXMLResponse($attributes, $nodes) {
		return static::makeXmlResponse(
			'<captureResponse '.static::buildXMLAttributes($attributes).' reportGroup="default">'.
				static::buildXMLNodes($nodes).
			'</captureResponse>'
		);
	}

	/**
	 * Make Sale XML Response
	 */
	public static function makeSaleXmlResponse($attributes, $nodes) {
		return static::makeXmlResponse(
			'<saleResponse '.static::buildXMLAttributes($attributes).' reportGroup="default">'.
				static::buildXMLNodes($nodes).
			'</saleResponse>'
		);
	}

	/**
	 * Make an XML Responses
	 */
	public static function makeXmlResponse($content) {
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
	private static function buildXMLAttributes($attributes) {
		$xml_attributes = '';

		foreach ($attributes as $attribute => $value) {
			$xml_attributes .= " $attribute='$value' ";
		}

		return $xml_attributes;
	}

	/**
	 * Build XML Nodes
	 */
	private static function buildXMLNodes($nodes) {
		$xml_nodes = '';

		foreach ($nodes as $node => $value) {
			$xml_nodes .= "<$node>$value</$node>\n";
		}

		return $xml_nodes;
	}	
	    
}