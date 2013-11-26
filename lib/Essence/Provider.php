<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use Essence\Configurable;
use Essence\Exception;
use Essence\Media;
use Essence\Log\Logger;



/**
 *	Base class for a Provider.
 *
 *	@package fg.Essence
 */

abstract class Provider {

	use Configurable;



	/**
	 *	Internal logger.
	 *
	 *	@var Essence\Log\Logger
	 */

	protected $_Logger = null;



	/**
	 *	Configuration options.
	 *
	 *	### Options
	 *
	 *	- 'prepare' callable( string $url ) A function to prepare the given URL.
	 *
	 *	@var array
	 */

	protected $_properties = array(
		'prepare' => 'trim'
	);



	/**
	 *	Constructor.
	 *
	 *	@param Essence\Log\Logger $Logger Logger.
	 */

	public function __construct( Logger $Logger ) {

		$this->_Logger = $Logger;
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

		if ( is_callable( $this->prepare )) {
			$url = call_user_func( $this->prepare, $url );
		}

		try {
			$Media = $this->_embed( $url, $options );
			$Media->setDefault( 'url', $url );
			$Media->complete( );
		} catch ( Exception $Exception ) {
			$this->_Logger->log(
				Logger::notice,
				"Unable to embed $url",
				array(
					'exception' => $Exception
				)
			);

			$Media = null;
		}

		return $Media;
	}



	/**
	 *	Does the actual fetching of informations.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media Embed informations.
	 *	@throws Essence\Exception
	 */

	abstract protected function _embed( $url, $options );

}
