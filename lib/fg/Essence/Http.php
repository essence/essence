<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	Handles HTTP related operations.
 *
 *	@package fg.Essence
 */

class Http {

	/**
	 *	CURL timeout, must be set before any method call, for example in the
	 *	bootstrap.
	 *	Set this variable to false to use file_get_contents( ) API instead of
	 *	cURL.
	 *
	 *	@var integer
	 */

	public static $curl = 10;



	/**
	 *	Singleton instance of Http.
	 *
	 *	@var \fg\Essence\Http
	 */

	protected static $_Instance = null;



	/**
	 *	CURL handle.
	 *
	 *	@var Dunno LOL
	 */

	protected $_curl = null;



	/**
	 *	Initializes cURL if needed.
	 */

	protected function __construct( ) {

		if ( self::$curl ) {
			$this->_curl = curl_init( );

			curl_setopt( $this->_curl, CURLOPT_HEADER, false );
			curl_setopt( $this->_curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $this->_curl, CURLOPT_CONNECTTIMEOUT, self::$curl );
		}
	}



	/**
	 *	Closes cURL if needed.
	 */

	public function __destruct( ) {

		if ( $this->_curl ) {
			curl_close( $this->_curl );
		}
	}



	/**
	 *	Returns a singleton instance of Http.
	 *
	 *	@return Http Singleton instance.
	 */

	protected static function _instance( ) {

		if ( self::$_Instance === null ) {
			self::$_Instance = new self( );
		}

		return self::$_Instance;
	}



	/**
	 *	Retrieves contents from the given URL.
	 *
	 *	@param string $url The URL fo fetch contents from.
	 *	@return string The fetched contents.
	 */

	public static function get( $url ) {

		$_this = self::_instance( );

		return $_this->_curl
			? $_this->_curlGet( $url )
			: $_this->_simpleGet( $url );
	}



	/**
	 *	Retrieves contents through file_get_contents( ).
	 *
	 *	@param string $url The URL fo fetch contents from.
	 *	@return string The fetched contents.
	 */

	protected function _simpleGet( $url ) {

		$contents = @file_get_contents( $url );

		if ( $contents === false ) {
			// let's assume the file doesn't exists
			throw new Exception\Http( 404, $url );
		}

		return $contents;
	}



	/**
	 *	Retrieves contents through cURL.
	 *
	 *	@param string $url The URL fo fetch contents from.
	 *	@return string The fetched contents.
	 */

	protected function _curlGet( $url ) {

		curl_setopt( $this->_curl, CURLOPT_URL, $url );

		$contents = curl_exec( $this->_curl );

		if ( $contents === false ) {
			throw new Exception\Http(
				curl_getinfo( $this->_curl, CURLINFO_HTTP_CODE ),
				$url
			);
		}

		return $contents;
	}
}
