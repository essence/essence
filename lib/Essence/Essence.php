<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



/**
 *	Gathers embed informations from URLs.
 *
 *	@package Essence
 */

class Essence {

	/**
	 *	Singleton instance of Essence.
	 *
	 *	@var \Essence\Essence
	 */

	protected static $_Instance = null;



	/**
	 *	A collection of providers to query.	
	 *
	 *	@var \Essence\ProviderCollection
	 */

	protected $_ProviderCollection = null;



	/**
	 *	An array of catched exceptions.
	 *
	 *	@var array
	 */

	protected $_errors = array( );



	/**
	 *	Constructor.
	 */

	protected function __construct( ) {

		$this->_ProviderCollection = new ProviderCollection( );
	}



	/**
	 *	Returns a singleton instance of Essence.
	 *	
	 *	@return \Media\Essence Singleton instance.
	 */

	protected static function _instance( ) {

		if ( self::$_Instance === null ) {
			self::$_Instance = new self( );
		}

		return self::$_Instance;
	}



	/**
	 *	Configures Essence to query the given providers.
	 *	Throws an exception if a Provider couldn't be found.
	 *
	 *	@see \Essence\ProviderCollection::load( )
	 *	@param array $providers An array of provider class names, relative to
	 *		the 'Provider' folder.
	 *	@throws \Essence\Exception 
	 */

	public static function configure( array $providers ) {

		$_this = self::_instance( );
		$_this->_ProviderCollection->load( $providers );
	}



	/**
	 *	If the url can be parser directly by one of the registered providers,
	 *	it is returned as is. Otherwise, the page is parsed to find such urls.
	 *
	 *	@param string $url The Url to extract.
	 */

	public static function extract( $url ) {

		$_this = self::_instance( );

		// if a provider can directly handle the url, there is no more work to do.

		if ( $_this->_ProviderCollection->hasProvider( $url )) {
			return array( $url );
		}

		try {
			$urls = self::_extractUrls( $url );
		} catch ( Exception $exception ) {
			$_this->_log( $exception );
			return array( );
		}

		$fetchable = array( );

		foreach ( $urls as $url ) {
			if ( $_this->_ProviderCollection->hasProvider( $url )) {
				$fetchable[] = $url;
			}
		}

		return array_values( array_unique( $fetchable )); // array_values reindexes the array
	}



	/**
	 *	Extracts URLs from a web page.
	 *	
	 *	@param string $url The web page to extract URLs from.
	 *	@return array Extracted URLs.
	 */

	protected static function _extractUrls( $url ) {

		$attributes = Html::extractAttributes(
			Http::get( $url ),
			array(
				'a' => array( 'href' ),
				'embed' => array( 'src' ),
				'iframe' => array( 'src' )
			)
		);

		$urls = array( );

		foreach ( $attributes['a'] as $a ) {
			$urls[] = $a['href'];
		}

		foreach ( $attributes['embed'] as $embed ) {
			$urls[] = $embed['src'];
		}

		foreach ( $attributes['iframe'] as $iframe ) {
			$urls[] = $iframe['src'];
		}

		return $urls;
	}



	/**
	 *	Fetches embed informations from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param int $maxwidth add maxwidth param to the request(optional)
	 *	@param int $maxheight add maxheight param to the request(optional)
	 *	@return \Essence\Media Embed informations.
	 */

	public static function embed( $url, $maxwidth = NULL, $maxheight = NULL ) {

		$_this = self::_instance( );

		$index = $_this->_ProviderCollection->providerIndex( $url );
		$Media = null;

		while ( $index !== false ) {
			$Provider = $_this->_ProviderCollection->provider( $index );
			$Media = null;

			try {
				$Media = $Provider->embed( $url, $maxwidth, $maxheight );
			} catch ( Exception $exception ) {
				$_this->_log( $exception );
			}

			$index = ( $Media === null )
				? $_this->_ProviderCollection->providerIndex( $url, $index )
				: false;
		}

		return $Media;
	}



	/**
	 *	Fetches embed informations from the given URLs.
	 *
	 *	@param array $urls An array of URLs to fetch informations from.
	 *	@return array An array of embed informations, indexed by URL.
	 */

	public static function embedAll( array $urls ) {

		$infos = array( );

		foreach ( $urls as $url ) {
			$infos[ $url ] = self::embed( $url );
		}

		return $infos;
	}



	/**
	 *	Returns all errors that occured.	
	 *
	 *	@return array All errors.
	 */

	public static function errors( ) {

		$_this = self::_instance( );

		return $_this->_errors;
	}



	/**
	 *	Returns the last error that occured.
	 *
	 *	@return \Essence\Exception|null The last exception, or null if there is
	 *		no errors.
	 */

	public static function lastError( ) {

		$_this = self::_instance( );

		if ( empty( $_this->_errors )) {
			return false;
		}

		return $_this->_errors[ count( $_this->_errors ) - 1 ];
	}



	/**
	 *	Logs an exception.
	 *
	 *	@param \Essence\Exception $exception The exception to log.
	 */

	protected function _log( Exception $exception ) {

		$this->_errors[] = $exception;
	}
}
