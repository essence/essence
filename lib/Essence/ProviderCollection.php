<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



/**
 *	A collection of providers which can find the provider of an url.
 *
 *	@package fg.Essence
 */

class ProviderCollection {

	/**
	 *	A list of provider configurations.
	 *
	 *	### Options
	 *
	 *	- 'name' string Name of the provider.
	 *		- 'class' string The provider class.
	 *		- 'filter' string|callable A regex or callback to filter URLs
	 *			that will be processed by the provider.
	 *		- ... mixed Provider specific options.
	 *
	 *	@var array
	 */

	protected $_config = array( );



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
	 *	@param array|string $providers An array of provider configurations,
	 *		or the path to a file returning such a configuration.
	 */

	public function __construct( $config = array( )) {

		if ( empty( $config )) {
			$config = ESSENCE_DEFAULT_CONFIG;
		}

		if ( is_array( $config )) {
			$this->_config = $config;
		} else if ( file_exists( $config )) {
			$this->_config = include $config;
		}
	}



	/**
	 *	Tells if a provider was found for the given url.
	 *
	 *	@param string $url An url which may be embedded.
	 *	@return mixed The url provider if any, otherwise null.
	 */

	public function hasProvider( $url ) {

		foreach ( $this->_config as $options ) {
			if ( $this->_filter( $options['filter'], $url )) {
				return true;
			}
		}

		return false;
	}



	/**
	 *	Finds providers of the given url.
	 *
	 *	@todo Use PHP generators to yield providers.
	 *	@param string $url An url which may be embedded.
	 *	@return array An array of Essence\Provider.
	 */

	public function providers( $url ) {

		$providers = array( );

		foreach ( $this->_config as $name => $options ) {
			if ( $this->_filter( $options['filter'], $url )) {
				$providers[ ] = $this->_provider( $name, $options );
			}
		}

		return $providers;
	}



	/**
	 *	Filters the URL with the given filter.
	 *
	 *	@param string|callable $filter Regex or callback to filter URL.
	 *	@param string $url URL to filter.
	 *	@return Whether the URL passes the filter or not.
	 */

	protected function _filter( $filter, $url ) {

		return is_callable( $filter )
			? call_user_func( $filter, $url )
			: preg_match( $filter, $url );
	}



	/**
	 *	Lazy loads a provider given its name and configuration.
	 *
	 *	@param string $name Name.
	 *	@param string $name Configuration.
	 *	@return Provider Instance.
	 */

	protected function _provider( $name, $options ) {

		if ( !isset( $this->_providers[ $name ])) {
			$class = $options['class'];

			if ( $class[ 0 ] !== '\\' ) {
				$class = "\\Essence\\Provider\\$class";
			}

			$this->_providers[ $name ] = new $class( $options );
		}

		return $this->_providers[ $name ];
	}
}
