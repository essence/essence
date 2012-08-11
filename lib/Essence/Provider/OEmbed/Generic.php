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

		parent::__construct( '#.*#', '', '' );
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return \Essence\Embed Embed informations.
	 */

	protected function _fetch( $url ) {

		$html = file_get_contents( $url );

		if ( $html === false ) {
			return;
		}

		$limit = stripos( $html, '</head>' );
		if ( $limit !== false ) {
			$html = substr( $html, 0, $limit );
		}

		preg_match_all( 
			'#<link[^/]*rel="alternate"[^/]*type="application/(?P<format>json|xml)\\+oembed"[^/]*href="(?P<url>[^"]*)"[^/]*/>#i', 
			$url, 
			$matches,
			PREG_SET_ORDER
		);

		// processing
	}
}
