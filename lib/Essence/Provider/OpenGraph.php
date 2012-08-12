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

		$metas = get_meta_tags( $url );
		$og = array( );

		foreach ( $metas as $meta ) {
			if ( strpos( $meta, 'og:' ) !== false ) {
				$og[] = substr( $meta, 3 );
			}
		}

		if ( empty( $og )) {
			throw new \Essence\Exception(
				'Unable to extract OpenGraph informations from ' . $url . '.'
			);
		}

		return new \Essence\Embed(
			$og,
			array(
				'site_name' => 'providerName',
				'image' => 'thumbnailUrl',
				'image:url' => 'thumbnailUrl',	
				'image:width' => 'width',
				'image:width' => 'height',
				'video:width' => 'width',
				'video:width' => 'height'
			)
		);
	}
}
