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
 *	@package Essence
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

	protected $_properties = [
		'prepare' => 'self::prepareUrl',
		'complete' => 'self::completeMedia'
	];



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

	public final function embed( $url, array $options = [ ]) {

		$Media = null;

		if ( is_callable( $this->prepare )) {
			$url = call_user_func( $this->prepare, $url, $options );
		}

		try {
			$Media = $this->_embed( $url, $options );
			$Media->setDefault( 'url', $url );

			if ( is_callable( $this->complete )) {
				call_user_func( $this->complete, $Media, $options );
			}
		} catch ( Exception $Exception ) {
			$this->_Logger->log(
				Logger::notice,
				"Unable to embed $url",
				[ 'exception' => $Exception ]
			);
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



	/**
	 *	Builds an HTML code from the given media's properties to fill its
	 *	'html' property.
	 *
	 *	@param Essence\Media $Media A reference to the Media.
	 *	@param array $options Embed options.
	 */

	public static function completeMedia( Media $Media, array $options = [ ]) {

		if ( $Media->has( 'html' )) {
			return;
		}

		$title = htmlspecialchars( $Media->get( 'title', $Media->url ));
		$description = $Media->has( 'description' )
			? htmlspecialchars( $Media->description )
			: $title;

		switch ( $Media->type ) {
			// builds an <img> tag pointing to the photo
			case 'photo':
				$Media->set( 'html', sprintf(
					'<img src="%s" alt="%s" width="%d" height="%d" />',
					$Media->url,
					$description,
					$Media->get( 'width', 500 ),
					$Media->get( 'height', 375 )
				));
				break;

			// builds an <iframe> tag pointing to the video
			case 'video':
				$Media->set( 'html', sprintf(
					'<iframe src="%s" width="%d" height="%d" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen />',
					$Media->url,
					$Media->get( 'width', 640 ),
					$Media->get( 'height', 390 )
				));
				break;

			// builds an <a> tag pointing to the original resource
			default:
				$Media->set( 'html', sprintf(
					'<a href="%s" alt="%s">%s</a>',
					$Media->url,
					$description,
					$title
				));
				break;
		}
	}
}
