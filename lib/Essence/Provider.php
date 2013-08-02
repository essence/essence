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

	protected $_Log = null;



	/**
	 *	Configuration options.
	 *
	 *	### Options
	 *
	 *	- 'prepare' callable( string $url ) A function to prepare the given URL.
	 *	- 'complete' callable( Essence\Media $Media ) A function to complete
	 *		the given media properties.
	 *
	 *	@var array
	 */

	protected $_properties = array(
		'prepare' => 'trim',
		'complete' => 'self::completeMedia'
	);



	/**
	 *	Constructor.
	 *
	 *	@param Essence\Log\Logger $Log Logger.
	 */

	public function __construct( Logger $Log = null ) {

		$this->_Log = $Log;
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

			if ( is_callable( $this->complete )) {
				$Media = call_user_func( $this->complete, $Media );
			}
		} catch ( Exception $Exception ) {
			if ( $this->_Log ) {
				$this->_Log->log(
					Logger::notice,
					"Unable to embed $url",
					array(
						'exception' => $Exception
					)
				);
			}

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



	/**
	 *	Builds an HTML code from the given media properties to fill its 'html'
	 *	property.
	 *
	 *	@param Essence\Media $Media Media.
	 *	@return Essence\Media Completed media.
	 */

	public static function completeMedia( Media $Media ) {

		if ( !$Media->has( 'html' )) {
			$title = htmlspecialchars( $Media->get( 'title', $Media->url ));

			switch ( $Media->type ) {
				// builds an <img> tag pointing to the photo
				case 'photo':
					$Media->set( 'html', sprintf(
						'<img src="%s" alt="%s" width="%d" height="%d" />',
						$Media->url,
						$title,
						$Media->get( 'width', 500 ),
						$Media->get( 'height', 375 )
					));
					break;

				// builds an <a> tag pointing to the original resource
				default:
					$Media->set( 'html', sprintf(
						'<a href="%s" alt="%s">%s</a>',
						$Media->url,
						$Media->has( 'description' )
							? htmlspecialchars( $Media->description )
							: $title,
						$title
					));
					break;
			}
		}

		return $Media;
	}
}
