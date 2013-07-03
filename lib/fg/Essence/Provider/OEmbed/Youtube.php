<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Youtube provider (http://www.youtube.com).
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Youtube extends \fg\Essence\Provider\OEmbed {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#youtube\.com|youtu\.be#i';



	/**
	 *	{@inheritDoc}
	 */

	protected $_endpoint = 'http://www.youtube.com/oembed?format=json&url=%s';



	/**
	 *	Refactors URLs like these :
	 *		- http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise
	 *		- http://www.youtube.com/v/oHg5SJYRHA0
	 *		- http://www.youtube.com/embed/oHg5SJYRHA0
	 *		- http://youtu.be/oHg5SJYRHA0
	 *
	 *	in such form :
	 *		- http://www.youtube.com/watch?v=oHg5SJYRHA0
	 *
	 *	@param string $url URL to refactor.
	 *	@return string Refactored URL.
	 */

	protected function _prepare( $url ) {

		$url = \fg\Essence\Provider::_prepare( $url );

		if ( preg_match( "#(v=|v/|embed/|youtu\.be/)([a-z0-9_-]+)#i", $url, $matches )) {
			return 'http://www.youtube.com/watch?v=' . $matches[ 2 ];
		}

		return $url;
	}
}
