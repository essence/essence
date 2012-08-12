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
		$limit = stripos( $html, '</head>' );

		if ( $limit !== false ) {
			$html = substr( $html, 0, $limit );
		}

		$result = preg_match_all( 
			'#<link(' .
				'[^>]*type=["\']([^"\']+(?P<format>json|xml)\\+oembed)' .
				'|' .
				'[^>]*href=["\'](?P<url>[^"\']+)' .
			')+#i',
			$html, 
			$matches,
			PREG_SET_ORDER
		);

		if ( $result ) {
			foreach ( $matches as $match ) {
				if ( !empty( $match['url']) && !empty( $match['format'])) {
					return $this->_fetchEndpoint( $match['url'], $match['format']);
				}
			}
		}

		throw new \Essence\Exception( 'Unable to find any endpoint.' );
	}
}
