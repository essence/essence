<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	A collection of providers which can find the provider of an url.
 *
 *	@package fg.Essence
 */

class ProviderCollection {

	/**
	 *	A list of providers.
	 *
	 *	@var array
	 */

	protected $_providers = array( );



	/**
	 *	Loads the given providers.
	 *
	 *	@see load( )
	 *	@param array $providers An array of provider class names, relative to
	 *		the 'Provider' folder.
	 */

	public function __construct( array $providers = array( )) {

		$this->load( $providers );
	}



	/**
	 *	Loads the given providers. If no particular provider is specified,
	 *	then all the available providers are loaded.
	 *	Throws an exception if a Provider couldn't be found.
	 *
	 *	@param array $providers An array of provider class names, relative to
	 *		the 'Provider' folder.
	 *	@throws \fg\Essence\Exception
	 */

	public function load( array $providers = array( )) {

		if ( empty( $providers )) {
			$path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Provider';

			$Package = new Package( $path );
			$providers = $Package->classes( array( ), true );
		}

		$this->_providers = array( );

		foreach ( $providers as $provider ) {
			$className = '\\fg\\Essence\\Provider\\' . str_replace( '/', '\\', $provider );
			$Reflection = null;

			try {
				$Reflection = new \ReflectionClass( $className );
			} catch ( \ReflectionException $Exception ) {
				throw new Exception( $Exception->getMessage( ));
			}

			if ( !$Reflection->isAbstract( )) {
				$this->_providers[ ] = $Reflection->newInstance( );
			}
		}
	}



	/**
	 *	Tells if a provider was found for the given url.
	 *
	 *	@param string $url An url which may be embedded.
	 *	@return mixed The url provider if any, otherwise null.
	 */

	public function hasProvider( $url ) {

		foreach ( $this->_providers as $Provider ) {
			if ( $Provider->canEmbed( $url )) {
				return true;
			}
		}

		return false;
	}



	/**
	 *	Finds providers of the given url.
	 *
	 *	@param string $url An url which may be embedded.
	 *	@return array An array of \fg\Essence\Provider.
	 */

	public function providers( $url ) {

		$providers = array( );

		foreach ( $this->_providers as $Provider ) {
			if ( $Provider->canEmbed( $url )) {
				$providers[ ] = $Provider;
			}
		}

		return $providers;
	}
}
