<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence\Provider\OEmbed;



/**
 *
 */

class Vimeo extends \Essence\Provider\OEmbed {

	/**
	 *
	 */

	public function __construct( ) {

		parent::__construct(
			'#vimeo\.com#i',
			'http://vimeo.com/api/oembed.json?url=%s'
		);
	}



	/**
	 *
	 */

	protected function _prepare( $url ) {

		if ( preg_match( "#player\.vimeo\.com/video/([0-9]+)#i", $url, $matches )) {
			return 'http://www.vimeo.com/' . $matches[ 1 ];
		}

		return $url;
	}
}
