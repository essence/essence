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
		$attributes = \Essence\Html::extractAttributes(
			$html,
			array(
				'meta' => array(
					'property' => '#^og:.+#i',
					'content'
				)
			)
		);

		$og = array( );

		foreach ( $attributes['meta'] as $attribute ) {
			$og[ $attribute['property']] = $attribute['content'];
		}

		return new \Essence\Embed(
			$og,
			array(
				'og:type' => 'type',
				'og:title' => 'title',	
				'og:description' => 'description',	
				'og:site_name' => 'providerName',
				'og:title' => 'title',
				'og:description' => 'description',
				'og:site_name' => 'providerName',
				'og:image' => 'thumbnailUrl',
				'og:image:url' => 'thumbnailUrl',	
				'og:image:width' => 'width',
				'og:image:height' => 'height',
				'og:video:width' => 'width',
				'og:video:height' => 'height',
				'og:url' => 'url'
			)
		);
	}
}
