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
	 *	Constructs the OpenGraph provider with a regular expression to match
	 *	the URLs it can handle.
	 */

	public function __construct( $pattern ) {

		parent::__construct( $pattern );

		$this->_Cache = new \fg\Essence\Cache\Volatile( );
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@return Media Embed informations.
	 */

	protected function _embed( $url ) {

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
			$og[ $meta['property']] = $meta['content'];
		}

		$this->_Cache->set( $url, $og );
		return $og;
	}
}
