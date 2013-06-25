<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@author Laughingwithu <laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider;

use fg\Essence\Exception;
use fg\Essence\Media;
use fg\Essence\Provider;
use fg\Essence\Cache\Volatile;
use fg\Essence\Utility\Registry;
use fg\Essence\Utility\Set;



/**
 *	Base class for an OpenGraph provider.
 *	This kind of provider extracts embed informations from OpenGraph meta tags.
 *
 *	@package fg.Essence.Provider
 */

abstract class OpenGraph extends Provider {

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

		$this->_Cache = new Volatile( );
	}



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) {

		$og = $this->_extractInformations( $url );

		if ( empty( $og )) {
			throw new Exception(
				'Unable to extract OpenGraph data.'
			);
		}

		return new Media(
			Set::reindex(
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

		$attributes = Registry::get( 'dom' )->extractAttributes(
			Registry::get( 'http' )->get( $url ),
			array(
				'meta' => array(
					'property' => '#^og:.+#i',
					'content'
				)
			)
		);

		$og = array( );

		foreach ( $attributes['meta'] as $meta ) {
			if ( !isset( $og[ $meta['property']])) {
				$og[ $meta['property']] = $meta['content'];
			}
		}

		if ( empty( $og['html'])) {
			$og['html'] = $this->_buildHtml( $og, $url );
		}

		$this->_Cache->set( $url, $og );
		return $og;
	}



	/**
	 *	Builds an HTML code from OpenGraph properties.
	 *
	 *	@param array $og OpenGraph properties.
	 *	@param string $url URL from which informations were fetched.
	 *	@return string Generated HTML.
	 */

	protected function _buildHtml( $og, $url ) {

		$html = '';
		$title = isset( $og['og:title'])
			? $og['og:title']
			: '';

		if ( isset( $og['og:video'])) {
			$html = sprintf(
				'<iframe src="%s" alt="%s" width="%s" height="%s" frameborder="0" allowfullscreen mozallowfullscreen webkitallowfullscreen></iframe>',
				$og['og:video'],
				$title,
				isset( $og['og:video:width'])
					? $og['og:video:width']
					: 560,
				isset( $og['og:video:height'])
					? $og['og:video:height']
					: 315
			);
		} else {
			$html = sprintf(
				'<a href="%s" alt="%s">%s</a>',
				isset( $og['og:url'])
					? $og['og:url']
					: $url,
				$title,
				$title
			);
		}

		return $html;
	}
}

