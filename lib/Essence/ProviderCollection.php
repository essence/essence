<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence;



/**
 *
 */

class ProviderCollection {

	/**
	 *	A list of providers.
	 *
	 *	@var array
	 */

	protected $_providers;



	/**
	 *
	 */

	public function load( array $providers ) {

		foreach ( $providers as $provider ) {
			$className = '\\Essence\\Provider\\' . str_replace( '/', '\\', $provider );

			if ( !class_exists( $className )) {
				throw new \Exception( "Unable to find a provider named '$className'." );
			}

			$this->_providers[ $className ] = new $className( );
		}
	}



	/**
	 *	Searches for the provider of the given url.
	 *
	 *	@param string $url The url .
	 *	@return mixed The url provider if any, otherwise null.
	 */

	public function hasProvider( $url ) {

		return ( $this->provider( $url ) !== null );
	}



	/**
	 *	Searches for the provider of the given url.
	 *
	 *	@param string $url The url .
	 *	@return mixed The url provider if any, otherwise null.
	 */

	public function provider( $url ) {

		foreach ( $this->_providers as $provider ) {
			if ( $provider->canFetch( $url )) {
				return $provider;
			}
		}

		return null;
	}
}
