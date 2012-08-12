<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;



/**
 *	Base class for an OpenGraph provider. 
 *	This kind of provider extracts embed informations from OpenGraph meta tags.
 *
 *	@package Essence.Provider
 */

abstract class OpenGraph extends \Essence\Provider {

	/**
	 *	Constructs the OpenGraph provider with a regular expression to match
	 *	the URLs it can handle.
	 */

	public function __construct( $pattern ) {

		parent::__construct( $pattern );
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
			'#<meta[^>]+' .
				'property=["\']og:(?P<property>[^"\']+)[^>]+' .
				'content=["\'](?P<content>[^"\']+)[^>]+' .
			'#i',
			$html, 
			$matches,
			PREG_SET_ORDER
		);

		if ( !$result ) {
			throw new \Essence\Exception(
				'Unable to extract OpenGraph informations.'
			);
		}

		$og = array( );

		foreach ( $matches as $match ) {
			$og[ $match['property']] = $match['content'];
		}

		return new \Essence\Embed(
			$og,
			array(
				'site_name' => 'providerName',
				'image' => 'thumbnailUrl',
				'image:url' => 'thumbnailUrl',	
				'image:width' => 'width',
				'image:height' => 'height',
				'video:width' => 'width',
				'video:height' => 'height'
			)
		);
	}
}
