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
	 *	The OEmbed endpoint.
	 *
	 *	@param string
	 */

	protected $_endpoint = '';



	/**
	 *	Constructs the OEmbed provider with a regular expression to match the
	 *	URLs it can handle, and an OEmbed JSON endpoint.
	 *
	 *	@param string $pattern 
	 *	@param string $jsonEndpoint The OEmbed JSON endpoint to query.
	 */

	public function __construct( $pattern, $jsonEndpoint ) {

		parent::__construct( $pattern );

		$this->_endpoint = $endpoint;
	}



	/**
	 *	Strips arguments and anchors from the given url.
	 *
	 *	@param string $url Url to prepare.
	 *	@return string Prepared url.
	 */

	protected function _prepare( $url ) {

		$questionMark = strpos( $url, '?' );

		if ( $questionMark === false ) {
			$sharp = strpos( $url, '#' );

			if ( $sharp !== false ) {
				$url = substr( $url, 0, $sharp );
			}
		} else {
			$url = substr( $url, 0, $questionMark );
		}

		return $url;
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return \Essence\Embed Embed informations.
	 */

	protected function _fetch( $url ) {

		$endpoint = sprintf( $this->_endpoint, urlencode( $url ));

		return json_decode( file_get_contents( $endpoint ), true );
	}
}
