<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



/**
 *	A collection of providers which can find the provider of an url.
 *
 *	@package Essence
 */

class ProviderCollection {
	
	/**
	 *	The base namespace for a Provider.
	 *
	 *	@var string
	 */

	protected $_baseNamespace = '\\Essence\\Provider\\';



	/**
	 *	A list of providers.
	 *
	 *	@var array
	 */

	protected $_providers = array( );



	/**
	 *	Loads the given list of providers.
	 *	Throws an exception if a Provider couldn't be found.
	 *
	 *	@param array $providers An array of provider class names, relative to
	 *		the 'Provider' folder.
	 *	@throws \Essence\Exception 
	 */

	public function load( array $providers ) {

		foreach ( $providers as $provider ) {
			$className = $this->_baseNamespace . str_replace( '/', '\\', $provider );

			if ( !isset( $this->_providers[ $className ])) {
				if ( !class_exists( $className )) {
					throw new \Essence\Exception(
						"Unable to find a provider named '$className'."
					);
				}

				$this->_providers[ $className ] = new $className( );
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

		return ( $this->provider( $url ) !== null );
	}



	/**
	 *	Searches for the provider of the given url.
	 *
	 *	@param string $url An url which may be embedded.
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
