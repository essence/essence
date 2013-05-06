<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@author Laughingwithu <laughingwithu@gmail.com>
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

		if ( empty( $og['html'])) {
			$og['html'] = $this->_buildHtml( $og );
		}

		$this->_Cache->set( $url, $og );

		return $og;
	}



	/**
	 *	Ensures that an there is always a html array available.
	 *
	 *	@param string $og to include array parsed by Essence.
	 *	@return array with html variable included.
	 */
	
	protected function _buildHtml( $og ) {

		$title = $og['og:title'];
		$html = '';

		// check if the preferred resource exists (ie. video < image < link) and
		// then builds an html string accordingly
		if ( isset( $og['og:video'])) {
			$html = '<iframe src="' . $og['og:video'] . '" alt="' . $title . '" width="560" height="315" frameborder="0" allowfullscreen mozallowfullscreen webkitallowfullscreen><p>Your browser does not support iframes.</p></iframe>'; // The dimensions 560 x 315 are generally standard and not all OG providers give dimensions
		} else if ( isset( $og['og:image'])) {
			$html = '<img src="' . $og['og:image'] . '" alt="' . $title . '">';
		} else {
			$html = '<a href="' . $og['og:url'] . '" target="_blank">' . $title . '</a>';
		}

		return $html;
	}
}

