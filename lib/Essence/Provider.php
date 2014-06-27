<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use Essence\Configurable;
use Essence\Exception;
use Essence\Media;
use Essence\Filter\Cascade;



/**
 *	Base class for a Provider.
 */

abstract class Provider {

	use Configurable;



	/**
	 *	Presenters.
	 *
	 *	@var Essence\Filter\Cascade
	 */

	protected $_Presenters = null;



	/**
	 *	Configuration options.
	 *
	 *	### Options
	 *
	 *	- 'prepare' callable( string $url ) A function to prepare the given URL.
	 *
	 *	@var array
	 */

	protected $_properties = [
		'prepare' => 'self::prepareUrl'
	];



	/**
	 *	Constructor.
	 *
	 *	@param array $presenters Presenters.
	 */

	public function __construct( array $presenters = [ ]) {

		$this->_Presenters = new Cascade( $presenters );
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media|null Embed informations, or null if nothing could be
	 *		fetched.
	 */

	public final function embed( $url, array $options = [ ]) {

		if ( is_callable( $this->prepare )) {
			$url = call_user_func( $this->prepare, $url, $options );
		}

		$Media = $this->_embed( $url, $options );
		$Media->setDefault( 'url', $url );

		return $this->_Presenters->filter( $Media );
	}



	/**
	 *	Does the actual fetching of informations.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media Embed informations.
	 *	@throws Essence\Exception
	 */

	abstract protected function _embed( $url, array $options );



	/**
	 *	Trims and returns the given string.
	 *
	 *	@param string $url URL.
	 *	@param array $options Embed options.
	 *	@return string Trimmed URL.
	 */

	public static function prepareUrl( $url, array $options = [ ]) {

		return trim( $url );
	}
}
