<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;

use Essence\Exception;
use Essence\Media;
use Essence\Provider;
use Essence\Dom\Consumer as DomConsumer;
use Essence\Http\Consumer as HttpConsumer;
use Essence\Utility\Hash;
use Essence\Utility\Json;
use Essence\Utility\Xml;



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

	protected $_properties = array(
		'prepare' => 'OEmbed::prepare',
		'endpoint' => '',
		'format' => self::json
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

		$position = strrpos( $string, $delimiter );
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

		if ( $this->endpoint ) {
			$endpoint = sprintf( $this->endpoint, urlencode( $url ));
			$format = $this->format;
		} else if ( !$this->_extractEndpoint( $url, $endpoint, $format )) {
			return null;
		}

		return $this->_embedEndpoint( $endpoint, $format, $options );
	}



	/**
	 *	Extracts an oEmbed endpoint from the given URL.
	 *
	 *	@param string $url URL from which to extract an endpoint.
	 *	@param string $endpoint The extracted endpoint.
	 *	@param string $format The extracted format.
	 *	@return boolean If an endpoint was extracted.
	 */

	protected function _extractEndpoint( $url, &$endpoint, &$format ) {

		$attributes = $this->_domParser( )->extractAttributes(
			$this->_httpClient( )->get( $url ),
			array(
				'link' => array(
					'rel' => '#alternate#i',
					'type',
					'href'
				)
			)
		);

		foreach ( $attributes['link'] as $link ) {
			if ( preg_match( '#(?<format>json|xml)#i', $link['type'], $matches )) {
				$endpoint = $link['href'];
				$format = $matches['format'];
				return true;
			}
		}

		return false;
	}



	/**
	 *	Fetches embed information from the given endpoint.
	 *
	 *	@param string $endpoint Endpoint to fetch informations from.
	 *	@param string $format Response format.
	 *	@return Media Embed informations.
	 */

	protected function _embedEndpoint( $endpoint, $format, $options ) {

		$response = $this->_httpClient( )->get(
			$this->_completeEndpoint( $endpoint, $options )
		);

		switch ( $format ) {
			case self::json:
				$data = Json::parse( $response );
				break;

			case self::xml:
				$data = Xml::parse( $response );
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
					'thumbnail_height' => 'thumbnailHeight'
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
				$endpoint .= ( strrpos( $endpoint, '?' ) === false ) ? '?' : '&';
				$endpoint .= http_build_query( $params );
			}
		}

		return $endpoint;
	}
}
