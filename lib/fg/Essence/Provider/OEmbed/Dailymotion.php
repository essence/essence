<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Dailymotion provider (http://www.dailymotion.com).
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Dailymotion extends \fg\Essence\Provider\OEmbed {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct(
			'#dailymotion\.com#i',
			'http://www.dailymotion.com/services/oembed?format=json&url=%s',
			self::json
		);
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return Media Embed informations.
	 */

	protected function _embed( $url ) {

		$Media = parent::_embed( $url );

		// we're getting the larger possible thumbnail, instead of the default
		// one given by dailymotion

		if ( !empty( $Media->thumbnailUrl )) {
			$Media->thumbnailUrl = str_replace(
				'jpeg_preview_large',
				'jpeg_preview_source',
				$Media->thumbnailUrl
			);
		}

		return $Media;
	}
}
