<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider;

use fg\Essence\Exception;
use fg\Essence\Media;
use fg\Essence\Provider;
use fg\Essence\Dom\Consumer as DomConsumer;
use fg\Essence\Http\Consumer as HttpConsumer;
use fg\Essence\Utility\Hash;



/**
 *	Base class for an OEmbed provider.
 *	This kind of provider extracts embed informations through the OEmbed protocol.
 *
 *	@package fg.Essence.Provider
 */

class OEmbed extends Provider {

	use DomConsumer;
	use HttpConsumer;



	/**
	 *	JSON response format.
	 *
	 *	@var string
	 */

	const json = 'json';



	/**
	 *	XML response format.
	 *
	 *	@var string
	 */

	const xml = 'xml';



	/**
	 *	### Options
	 *
	 *	- 'endpoint' string The OEmbed endpoint.
	 *	- 'format' string The expected response format.
	 */

	protected $_options = array(
		'prepare' => 'OEmbed::prepare',
		'endpoint' => '',
		'format' => self::json
	);



	/**
	 *	JSON error messages.
	 *
	 *	@var array
	 */

	protected $_jsonErrors = array(
		JSON_ERROR_NONE => 'no error',
		JSON_ERROR_DEPTH => 'depth error',
		JSON_ERROR_STATE_MISMATCH => 'state mismatch error',
		JSON_ERROR_CTRL_CHAR => 'control character error',
		JSON_ERROR_SYNTAX => 'syntax error',
		JSON_ERROR_UTF8 => 'UTF-8 error'
	);



	/**
	 *	Strips arguments and anchors from the given URL.
	 *
	 *	@param string $url Url to prepare.
	 *	@return string Prepared url.
	 */

	public static function prepare( $url ) {

		$url = trim( $url );

		if ( !self::strip( $url, '?' )) {
			self::strip( $url, '#' );
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

	public static function strip( &$string, $delimiter ) {

		$position = strpos( $string, $delimiter );
		$found = ( $position !== false );

		if ( $found ) {
			$string = substr( $string, 0, $position );
		}

		return $found;
	}



	/**
	 *	{@inheritDoc}
	 *
	 *	@note If no endpoint was specified in the configuration, the page at
	 *		the given URL will be parsed to find one.
	 */

	protected function _embed( $url, $options ) {

		$Media = null;

		if ( empty( $this->_options['endpoint'])) {
			$endpoint = $this->_extractEndpoint( $url );

			if ( $endpoint ) {
				$Media = $this->_embedEndpoint(
					$endpoint['url'],
					$endpoint['format'],
					$options
				);
			}
		} else {
			$Media = $this->_embedEndpoint(
				sprintf( $this->_options['endpoint'], urlencode( $url )),
				$this->_options['format'],
				$options
			);
		}

		return $Media;
	}



	/**
	 *	Extracts an oEmbed endpoint from the given URL.
	 *
	 *	@param string $url
	 */

	protected function _extractEndpoint( $url ) {

		$attributes = $this->_dom( )->extractAttributes(
			$this->_http( )->get( $url ),
			array(
				'link' => array(
					'rel' => '#alternate#i',
					'type',
					'href'
				)
			)
		);

		$endpoint = false;

		foreach ( $attributes['link'] as $link ) {
			if ( preg_match( '#(?<format>json|xml)#i', $link['type'], $matches )) {
				$endpoint = array(
					'url' => $link['href'],
					'format' => $matches['format']
				);

				break;
			}
		}

		return $endpoint;
	}



	/**
	 *	Fetches embed information from the given endpoint.
	 *
	 *	@param string $endpoint Endpoint to fetch informations from.
	 *	@param string $format Response format.
	 *	@return Media Embed informations.
	 */

	protected function _embedEndpoint( $endpoint, $format, $options ) {

		$response = $this->_http( )->get(
			$this->_completeEndpoint( $endpoint, $options )
		);

		switch ( $format ) {
			case self::json:
				$data = $this->_parseJson( $response );
				break;

			case self::xml:
				$data = $this->_parseXml( $response );
				break;

			default:
				throw new Exception( 'Unsupported format.' );
		}

		return new Media(
			Hash::reindex(
				$data,
				array(
					'author_name' => 'authorName',
					'author_url' => 'authorUrl',
					'provider_name' => 'providerName',
					'provider_url' => 'providerUrl',
					'cache_age' => 'cacheAge',
					'thumbnail_url' => 'thumbnailUrl',
					'thumbnail_width' => 'thumbnailWidth',
					'thumbnail_height' => 'thumbnailHeight',
				)
			)
		);
	}



	/**
	 *	If some options were specified, append them to the endpoint URL.
	 *
	 *	@param string $endpoint Endpoint URL.
	 *	@return string Completed endpoint URL.
	 */

	protected function _completeEndpoint( $endpoint, $options ) {

		if ( !empty( $options )) {
			$params = array_intersect_key(
				$options,
				array(
					'maxwidth' => '',
					'maxheight' => ''
				)
			);

			if ( !empty( $params )) {
				$endpoint .= ( strpos( $endpoint, '?' ) === false ) ? '?' : '&';
				$endpoint .= http_build_query( $params );
			}
		}

		return $endpoint;
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
			throw new Exception(
				'Error parsing JSON response: '
				. $this->_jsonErrors[ json_last_error( )]
				. '.'
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

		$internal = libxml_use_internal_errors( true );
		$data = array( );
		$it = new \SimpleXmlIterator( $xml, null );

		foreach ( $it as $key => $value ) {
			$data[ $key ] = strval( $value );
		}

		libxml_use_internal_errors( $internal );
		return $data;
	}
}
