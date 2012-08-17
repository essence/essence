<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OEmbed;



/**
 *	Dailymotion Provider (http://www.dailymotion.com).
 *
 *	@package Essence.Provider.OEmbed
 */

class Dailymotion extends \Essence\Provider\OEmbed {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct(
			'#dailymotion\.com#i',
			'http://www.dailymotion.com/services/oembed?format=json&url=%s',
			'json'
		);
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return \Essence\Embed Embed informations.
	 */

	protected function _fetch( $url ) {

		$Embed = parent::_fetch( $url );

		// we're getting the larger possible thumbnail, instead of the default
		// one given by dailymotion

		if ( !empty( $Embed->thumbnailUrl )) {
			$Embed->thumbnailUrl = str_replace(
				'jpeg_preview_large',
				'jpeg_preview_source',
				$Embed->thumbnailUrl
			);
		}

		return $Embed;
	}
}
