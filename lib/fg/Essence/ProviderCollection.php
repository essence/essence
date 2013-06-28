<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

use fg\Essence\Utility\Package;
use fg\Essence\Utility\Set;



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

	protected $_Package = null;



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

		$this->_Package = new Package(
			dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Provider'
		);

		$this->load( $providers );
	}



	/**
	 *	Loads the given providers. If no particular provider is specified,
	 *	then all the available providers are loaded.
	 *	Throws an exception if a Provider couldn't be found.
	 *
	 *	@param array $providers An array of provider class names, relative to
	 *		the 'Provider' folder.
	 *	@throws fg\Essence\Exception
	 */

	public function load( array $providers = array( )) {

		$excludeGenerics = empty( $providers );

		if ( $excludeGenerics ) {
			$providers = $this->_Package->classes( );
		}

		$providers = Set::normalize( $providers, array( ));

		foreach ( $providers as $name => $options ) {
			$Reflection = new \ReflectionClass(
				$this->_fullyQualified( $name )
			);

			if ( !$Reflection->isAbstract( )) {
				$Provider = $Reflection->newInstance( $options );

				if ( $Provider->isGeneric( )) {
					if ( !$excludeGenerics ) {
						$this->_providers[ ] = $Provider;
					}
				} else {
					// regular providers are pushed to the front to take
					// precedence over the generic ones.
					array_unshift( $this->_providers, $Provider );
				}
			}
		}
	}



	/**
	 *	Returns the fully qualified class name (FQCN) for the given provider
	 *	name. If the name happens to be a FQCN, it is returned as is.
	 *
	 *	@param string $name Provider name.
	 *	@param string FQCN.
	 */

	protected function _fullyQualified( $name ) {

		return ( $name[ 0 ] !== '\\' )
			? '\\fg\\Essence\\Provider\\' . str_replace( '/', '\\', $name )
			: $name;
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
	 *	@return array An array of fg\Essence\Provider.
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
