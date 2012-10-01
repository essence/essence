<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Generic OEmbed provider.
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Generic extends \fg\Essence\Provider\OEmbed {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct( self::anything, '', '' );
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return Media Embed informations.
	 */

	protected function _embed( $url ) {

		$attributes = \fg\Essence\Html::extractAttributes(
			\fg\Essence\Http::get( $url ),
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
				return $this->_embedEndpoint( $link['href'], array_shift( $matches ));
			}
		}

		throw new \fg\Essence\Exception( 'Unable to find any endpoint.' );
	}
}
