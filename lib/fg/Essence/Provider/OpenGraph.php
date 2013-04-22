<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider;



/**
 *	Base class for an OpenGraph provider.
 *	This kind of provider extracts embed informations from OpenGraph meta tags.
 *
 *	@package fg.Essence.Provider
 */

abstract class OpenGraph extends \fg\Essence\Provider {

	/**
	 *	A cache for extracted informations.
	 *
	 *	@var fg\Essence\Cache\Volatile
	 */

	protected $_Cache = null;



	/**
	 *	{@inheritDoc}
	 */

	public function __construct( array $options = array( )) {

		parent::__construct( $options );

		$this->_Cache = new \fg\Essence\Cache\Volatile( );
	}



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) {

		$og = $this->_extractInformations( $url );

		if ( empty( $og )) {
			throw new \fg\Essence\Exception(
				'Unable to extract OpenGraph data.'
			);
		}

		return new \fg\Essence\Media(
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



	/**
	 *	Extracts OpenGraph informations from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return array Extracted informations.
	 */

	protected function _extractInformations( $url ) {

		if ( $this->_Cache->has( $url )) {
			return $this->_Cache->get( $url );
		}

		$attributes = \fg\Essence\Registry::get( 'dom' )->extractAttributes(
			\fg\Essence\Registry::get( 'http' )->get( $url ),
			array(
				'meta' => array(
					'property' => '#^og:.+#i',
					'content'
				)
			)
		);

		$og = array( );

		foreach ( $attributes['meta'] as $meta ) {
			if ( isset($og[ $meta['property']]) ) { // Take only the first value
				continue;
			}

			$og[ $meta['property']] = $meta['content'];
		}

		$og = $this->_insertHtml( $og );

		$this->_Cache->set( $url, $og );
		return $og;
		}
		
		
	protected function _insertHtml($og) {
	// check to see if "html" set
		If (isset($og[html])) {
			// Nothing to do here
		}
		else {
		// Assign OG main attributes to the four specified by Oembed
		$rich = Array("rich","video");
		$image = Array("photo","music","article","movie");
		$link = Array("link","url");
	
		// add the html variable based on content type
			if (array_intersect($rich, $og)) {
				$og['html'] = "<iframe src='" . $og["og:video"] . "'></iframe>";
			}
			else if (array_intersect($image, $og)) {
				$og['html'] = "<img src='" . $og["og:image"] . "'></iframe>";
			}
			else if (array_intersect($link, $og)) {
				$og['html'] = "<a href='" . $og["og:url"] . "'></a>";
			}
			else {
				// Nothing to do here
			}
		}
	    return($og);
	}
}
