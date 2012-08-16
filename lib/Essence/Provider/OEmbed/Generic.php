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

		$attributes = \Essence\Html::extractAttributes(
			\Essence\Http::get( $url ),
			array(
				'link' => array(
					'rel' => '#alternate#i',
					'type',
					'href'
				)
			)
		);

		foreach ( $attributes['link'] as $link ) {
			if ( preg_match( '#json|xml#i', $link['type'], $matches )) {
				return $this->_fetchEndpoint( $link['href'], $format );
			}
		}

		throw new \Essence\Exception( 'Unable to find any endpoint.' );
	}
}
