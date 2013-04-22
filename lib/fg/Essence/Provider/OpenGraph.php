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

		$og = $this->_insertHtml($og);

		$this->_Cache->set( $url, $og );

		return $og;
	}


/**
 *	Ensures that an there is always a html array available.
 *
 *	@param string $og to include array parsed by Essence.
 *	@return array with html variable included.
 *  Contribution by laughingwithu@gmail.com
 */
	
	protected function _insertHtml($og) {
	// End function where "html" is already set
		if (isset($og['html'])) {
			// Nothing to do
		}
		else { 
			// get the url to the preferred resource - ie video < image < link
			if (isset($og['og:video'])){
				$url = $og['og:video'];
			}
			else if (isset($og['og:image'])){
				$url = $og['og:image'];
			}
			else {
				$url = $og['og:url'];
			}
			// Assign OG attributes to match the format of the html that will be most useful
			$type_array = array(
				array('check' => array("picture", "article", "image","photo","book","video.movie","video.episode","video.tv_show","video.other","music.song","music.album","music.radio_station","music.playlist"), 'format' => '<img src="%s" alt="%s">'),
				array('check' => array("rich", "video","prezi_for_facebook:prezi"), 'format' => '<iframe src="%s" alt="%s"><p>Your browser does not support iframes.</p></iframe>'),
				array('check' => array("link", "website","url"), 'format' => '<a href="%s">%s</a>'));
			// Run through each check until we get a hit on the type
			foreach ($type_array as &$ti) {
				if (array_intersect($og, $ti['check'])) {
				$og['html'] = sprintf($ti['format'], $url, $og['og:title']);
				}
			}
		}
		return $og;		
	}
}