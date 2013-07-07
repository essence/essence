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
	 *	Provider options, obtained from merging constructor options to the
	 *	default ones.
	 *
	 *	### Options
	 *
	 *	- 'prepare' callable( string $url ) A function to prepare the given URL.
	 *	- 'polish' callable( fg\Essence\Media $Media ) A function to polish
	 *		the given media.
	 *
	 *	@var array
	 */

	protected $_options = array(
		'prepare' => 'trim'
	);



	/**
	 *	Constructs the Provider with a set of options to configure its behavior.
	 *
	 *	@param array $options Configuration options.
	 */

	public function __construct( array $options = array( )) {

		$parentVars = get_class_vars( get_parent_class( $this ));
		$parentOptions = $parentVars['_options'];

		if (
			!empty( $parentOptions )
			&& is_array( $this->_options )
			&& ( $this->_options != $parentOptions )
		) {
			$this->_options = array_merge( $parentOptions, $this->_options );
		}

		$this->_options = array_merge( $this->_options, $options );
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

		$prepare = $this->_options['prepare'];

		if ( is_callable( $prepare )) {
			$url = $prepare( $url );
		}

		$Media = $this->_embed( $url, $options );

		if ( $Media && !$Media->get( 'url' )) {
			$Media->set( 'url', $url );
		}

		return $Media;
	}



	/**
	 *	Does the actual fetching of informations.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media Embed informations.
	 *	@throws fg\Essence\Exception
	 */

	abstract protected function _embed( $url, $options );

}
