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

		$this->_providers = array( );

		foreach ( $providers as $provider ) {
			$className = '\\Essence\\Provider\\'
				. str_replace( '/', '\\', $provider );

			if ( !class_exists( $className )) {
				throw new \Essence\Exception(
					"Unable to find a provider named '$provider'."
				);
			}

			$this->_providers[] = new $className( );
		}
	}



	/**
	 *	Tells if a provider was found for the given url.
	 *
	 *	@param string $url An url which may be embedded.
	 *	@return mixed The url provider if any, otherwise null.
	 */

	public function hasProvider( $url ) {

		return ( $this->providerIndex( $url ) !== false );
	}



	/**
	 *	Searches for a provider of the given url, and returns its index.
	 *
	 *	@param string $url An url which may be embedded.
	 *	@param integer $offset The search will start from this offset.
	 *	@return \Essence\Provider|false The url provider index if any,
	 *		otherwise false.
	 */

	public function providerIndex( $url, $offset = 0 ) {

		if ( !empty( $this->_providers )) {
			for ( $i = $offset; $i < count( $this->_providers ); $i++ ) {
				if ( $this->_providers[ $i ]->canEmbed( $url )) {
					return $i;
				}
			}
		}

		return false;
	}



	/**
	 *	Returns the provider at the given index.
	 *
	 *	@param integer $index The index of the provider.
	 *	@return \Essence\Provider|null The url provider if any, otherwise null.
	 */

	public function provider( $index ) {

		return isset( $this->_providers[ $index ])
			? $this->_providers[ $index ]
			: null;
	}
}
