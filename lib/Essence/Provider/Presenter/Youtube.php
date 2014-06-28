<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\Presenter;

use Essence\Media;



/**
 *
 */

class Youtube {

	/**
	 *	Available thumbnail formats.
	 */

	const small = 'small';
	const medium = 'medium';
	const large = 'large';



	/**
	 *	Thumbnail format.
	 *
	 *	@var array
	 */

	protected $_thumbnailFormat = '';



	/**
	 *	Constructor.
	 *
	 *	@param string $thumbnailFormat Thumbnail format.
	 */

	public function __construct( $thumbnailFormat ) {

		$this->_thumbnailFormat = $thumbnailFormat;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function filter( Media $Media ) {

		$url = $Media->get( 'thumbnailUrl' );

		if ( $url ) {
			switch ( $this->_thumbnailFormat ) {
				case self::small:
					$url = str_replace( 'hqdefault', 'default', $url );
					break;

				case self::medium:
					$url = str_replace( 'hqdefault', 'mqdefault', $url );
					break;

				case self::large:
				default:
					// unchanged
					break;
			}

			$Media->set( 'thumbnailUrl', $url );
		}

		return $Media;
	}
}
