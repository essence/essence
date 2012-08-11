<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;



/**
 *	Base class for an OEmbed provider.
 *	This kind of provider extracts embed informations through the OEmbed protocol.
 *
 *	@package Essence.Provider
 */

class OEmbed extends \Essence\Provider {

	/**
	 *	The expected response format.
	 *
	 *	@param string
	 */

	protected $_format = '';



	/**
	 *	The OEmbed endpoint.
	 *
	 *	@param string
	 */

	protected $_endpoint = '';



	/**
	 *	Constructs the OEmbed provider with a regular expression to match the
	 *	URLs it can handle, and an OEmbed JSON endpoint.
	 *
	 *	@param string $pattern A regular expression to match URLs.
	 *	@param string $endpoint The OEmbed endpoint url.
	 *	@param string $format The expected response format.
	 */

	public function __construct( $pattern, $endpoint, $format = '' ) {

		parent::__construct( $pattern );

		$this->_endpoint = $endpoint;
		$this->_format = $format;
	}



	/**
	 *	Strips arguments and anchors from the given url.
	 *
	 *	@param string $url Url to prepare.
	 *	@return string Prepared url.
	 */

	protected function _prepare( $url ) {

		if ( !$this->_strip( $url, '?' )) {
			$this->_strip( $url, '#' );
		}

		return $url;
	}



	/**
	 *	Strips the end of a string after a delimiter.
	 *
	 *	@param string $string The string to strip.
	 *	@param string $delimiter The delimiter from which to strip the string.
	 *	@return boolean True if the string was modified, otherwise false.
	 */

	protected function _strip( &$string, $delimiter ) {

		$position = strpos( $string, $delimiter );

		if ( $position !== false ) {
			$string = substr( $string, 0, $position );
		}

		return ( $position !== false );
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return \Essence\Embed Embed informations.
	 */

	protected function _fetch( $url ) {

		$endpoint = sprintf( $this->_endpoint, urlencode( $url ));
		$response = file_get_contents( $endpoint );

		if ( $response === false ) {
			throw new \Essence\Exception(
				'Unable to get a response from ' . $endpoint . '.'
			);
		}

		switch ( $this->_format ) {
			case 'json':
				$data = $this->_parseJson( $response );
				break;

			case 'xml':
				$data = $this->_parseXml( $response );
				break;

			default:
				$data = array( );
				break;
		}

		return new \Essence\Embed( $data );
	}



	/**
	 *	Parses a JSON response and returns an array of data.
	 *
	 *	@param string $json JSON document.
	 *	@return array Data.
	 */

	protected function _parseJson( $json ) {

		$data = json_decode( $json, true );

		if ( $data === null ) {
			throw new \Essence\Exception(
				'Unable to parse json response: ' . json_last_error( ) . '.'
			);
		}

		return $data;
	}



	/**
	 *	Parses an XML response and returns an array of data.
	 *	
	 *	@param string $xml XML document.
	 *	@return array Data.
	 */

	protected function _parseXml( $xml ) {

		$data = array( );
		$it = new \SimpleXmlIterator( $xml, null );

		foreach( $it as $key => $value ) {
			$data[ $key ] = strval( $value );
		}

		return $data;
	}
}
