<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	Base class for a Provider.
 *
 *	@package fg.Essence
 */

abstract class Provider {

	/**
	 *	A regular expression that doesn't match anything.
	 *
	 *	@var string
	 */

	const nothing = '#(?=a)b#';



	/**
	 *	A regular expression that matches anything.
	 *
	 *	@var string
	 */

	const anything = '#.*#';



	/**
	 *	A regular expression used to determine if an URL can be handled by the
	 *	provider.
	 *
	 *	@var string
	 */

	protected $_pattern = self::nothing;



	/**
	 *	Tells if the provider is generic.
	 *
	 *	@var boolean
	 */

	protected $_generic = false;



	/**
	 *	Provider options, obtained from merging constructor options to the
	 *	default ones.
	 *
	 *	@var array
	 */

	protected $_options = array( );



	/**
	 *	Default options to be merged with the ones given to the constructor.
	 *
	 *	@var array
	 */

	protected $_defaults = array( );



	/**
	 *	Constructs the Provider with a set of options to configure its behavior.
	 *
	 *	@param array $options Configuration options.
	 */

	public function __construct( array $options = array( )) {

		$this->_options = empty( $options )
			? $this->_defaults
			: array_merge( $this->_defaults, $options );
	}



	/**
	 *	Tells if the provider is generic, i.e. if it will attempt to fetch
	 *	information from any URL.
	 *
	 *	@param boolean Whether the provider is generic or not.
	 */

	public function isGeneric( ) {

		return $this->_generic;
	}



	/**
	 *	Tells if the provider can fetch embed informations from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 */

	public function canEmbed( $url ) {

		return ( boolean ) preg_match( $this->_pattern, $url );
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media|null Embed informations, or null if nothing could be
	 *		fetched.
	 */

	public final function embed( $url, array $options = array( )) {

		$url = $this->_prepare( $url );
		$Media = $this->_embed( $url, $options );

		if ( empty( $Media->url )) {
			$Media->url = $url;
		}

		return $Media;
	}



	/**
	 *	Prepares an URL before fetching its contents. This method can be
	 *	overloaded in subclasses to do some preprocessing.
	 *
	 *	@param string $url URL to prepare.
	 *	@return string Prepared URL.
	 */

	protected function _prepare( $url ) {

		return trim( $url );
	}



	/**
	 *	Does the actual fetching of informations.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media Embed informations.
	 *	@throws \fg\Essence\Exception
	 */

	abstract protected function _embed( $url, $options );

}
