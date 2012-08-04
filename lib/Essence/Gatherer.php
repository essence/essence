<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence;



/**
 *
 */

class Gatherer {

	/**
	 *	
	 */

	protected $_ProviderCollection = null;



	/**
	 *	Constructs the providers required in the settings.
	 */

	protected function __construct( ) {

		$this->_ProviderCollection = new ProviderCollection( );
	}



	/**
	 *
	 */

	protected static function _instance( ) {

		static $Instance = null;

		if ( $Instance === null ) {
			$Instance = new self( );
		}

		return $Instance;
	}



	/**
	 *
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

	public function extract( $url ) {

		$_this = self::_instance( );

		// si on demande une page de lecture connue, pas besoin de parser la page

		if ( $_this->_ProviderCollection->hasProvider( $url )) {
			return array( $url );
		}

		// on récupère la page

		$html = file_get_contents( $url );

		if ( $html === false ) {
			return array( );
		}

		// on extrait les urls possibles

		$result = preg_match_all(
			'#<(a|iframe|embed)[^>]+(href|src)="(?P<source>[^"]+)"#i',
			$html,
			$matches
		);

		$urls = array( );

		foreach ( $matches['source'] as $source ) {
			if ( $this->_ProviderCollection->hasProvider( $source )) {
				$urls[] = $source;
			}
		}

		return array_values( array_unique( $urls )); // array_values reindexes the array
	}



	/**
	 *	@return Embed  
	 */

	public function fetch( $url ) {

		$_this = self::_instance( );
		$provider = $_this->_ProviderCollection->provider( $url );

		return ( $provider === null )
			? null
			: $provider->fetch( $url );
	}



	/**
	 *	
	 */

	public function fetchAll( array $urls ) {
		
		$infos = array( );

		foreach ( $urls as $url ) {
			$data = $this->fetch( $url );

			if ( $data ) {
				$infos[] = $data;
			}
		}

		return $infos;
	}
}
