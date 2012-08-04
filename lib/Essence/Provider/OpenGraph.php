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

class OpenGraph extends \Essence\Provider {

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

		$html = file_get_contents( $url );

		if ( $html === false ) {
			return array( );
		}

		$data = $this->_extract( $html );

		return new \Essence\Embed(
			$data,
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



	/**
	 *	Extracts OpenGraph meta properties from a HTML document.
	 *
	 *	@param string $html HTML document.
	 *	@return array OpenGraph properties.
	 */

	protected function _extract( $html ) {

		$properties = array( );

		// we're trying to reduce the size of the html to parse

		if ( preg_match( '#<head[^>]*>(?P<head>.*)</head>#i', $html, $matches )) {
			$html = $matches['head'];
		}

		$count = preg_match_all(
			'#<meta[^>]*property="og:(?P<property>[^"]+)"[^>]*content="(?P<content>[^"]+)"#i',
			$html,
			$matches,
			PREG_SET_ORDER
		);

		if ( $count ) {
			foreach ( $matches as $match ) {
				$properties[ $match['property']] = $match['content'];
			}
		}

		return $properties;
	}
}
