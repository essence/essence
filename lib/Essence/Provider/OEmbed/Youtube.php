<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence\Provider\OEmbed;



/**
 *
 */

class Youtube extends \Essence\Provider\OEmbed {

	/**
	 *
	 */

	public function __construct( ) {

		parent::__construct(
			'#youtube\.com|youtu\.be#i',
			'http://www.youtube.com/oembed?format=json&url=%s'
		);
	}



	/**
	 *	Refactors urls like these :
	 *		- http://www.youtube.com/v/eatk8J8PwW4
	 *		- http://www.youtube.com/watch?v=eatk8J8PwW4&noise=noise
	 *		- http://wwW.youtube.com/embed/eatk8J8PwW4
	 *		- http://youtu.be/eatk8J8PwW4
	 *
	 *	in such form :
	 *		- http://www.youtube.com/watch?v=eatk8J8PwW4
	 */

	protected function _prepare( $url ) {

		if ( preg_match( "#(v=|v/|embed/|youtu\.be/)([a-z0-9_-]+)#i", $url, $matches )) {
			return 'http://www.youtube.com/watch?v=' . $matches[ 2 ];
		}

		return $url;
	}
}
