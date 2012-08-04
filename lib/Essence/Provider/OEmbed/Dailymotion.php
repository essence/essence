<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence\Provider\OEmbed;



/**
 *
 */

class Dailymotion extends \Essence\Provider\OEmbed {

	/**
	 *
	 */

	public function __construct( ) {

		parent::__construct(
			'#dailymotion\.com#i',
			'http://www.dailymotion.com/services/oembed?format=json&url=%s'
		);
	}



	/**
	 *	
	 */

	protected function _fetch( $url ) {

		$data = parent::_fetch( $url );

		if ( is_array( $data ) && isset( $data['thumbnail_url'])) {
			$data['thumbnail_url'] = str_replace( 'jpeg_preview_large', 'jpeg_preview_source', $data['thumbnail_url']);
		}

		return $data;
	}
}
