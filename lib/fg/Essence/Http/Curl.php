<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Http;



/**
 *	Handles HTTP related operations.
 *
 *	@package fg.Essence.Http
 */

class Curl implements \fg\Essence\Http {

	/**
	 *	CURL handle.
	 *
	 *	@var Dunno LOL
	 */

	protected $_curl = null;



	/**
	 *	Initializes cURL with the given settings.
	 *
	 *	@param array cURL options.
	 */

	public function __construct( array $settings = array( )) {

		$this->_curl = curl_init( );

		if ( $settings ) {
			curl_setopt_array( $this->_curl, $settings );
		}
	}



	/**
	 *	Closes cURL connexion.
	 */

	public function __destruct( ) {

		curl_close( $this->_curl );
	}



	/**
	 *	Retrieves contents from the given URL.
	 *
	 *	@param string $url The URL fo fetch contents from.
	 *	@return string The fetched contents.
	 *	@throws \fg\Essence\Http\Exception
	 */

	public function get( $url ) {

		curl_setopt( $this->_curl, CURLOPT_URL, $url );

		$contents = curl_exec( $this->_curl );

		if ( $contents === false ) {
			throw new Exception(
				curl_getinfo( $this->_curl, CURLINFO_HTTP_CODE ),
				$url
			);
		}

		return $contents;
	}
}
