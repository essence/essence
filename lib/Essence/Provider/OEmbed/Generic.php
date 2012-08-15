<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OEmbed;



/**
 *	Generic OEmbed provider.
 *
 *	@package Essence.Provider.OEmbed
 */

class Generic extends \Essence\Provider\OEmbed {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct( \Essence\Provider::anything, '', '' );
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return \Essence\Embed Embed informations.
	 */

	protected function _fetch( $url ) {

		$html = \Essence\Http::get( $url );
		$attributes = \Essence\Html::extractAttributes(
			$html,
			array(
				'link' => array(
					'type' => '#oembed$#i',
					'href'
				)
			)
		);

		foreach ( $attributes as $attribute ) {
			if ( preg_match( '#json|xml#i', $attribute['type'], $matches )) {
				return $this->_fetchEndpoint( $attribute['href'], $format );
			}
		}

		throw new \Essence\Exception( 'Unable to find any endpoint.' );
	}
}
