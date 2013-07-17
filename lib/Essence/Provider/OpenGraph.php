<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@author Laughingwithu <laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;

use Essence\Exception;
use Essence\Media;
use Essence\Provider;
use Essence\Dom\Parser as DomParser;
use Essence\Http\Client as HttpClient;
use Essence\Utility\Hash;



/**
 *	Base class for an OpenGraph provider.
 *	This kind of provider extracts embed informations from OpenGraph meta tags.
 *
 *	@package fg.Essence.Provider
 */

class OpenGraph extends Provider {

	/**
	 *	Internal HTTP client.
	 *
	 *	@var Essence\Http\Client
	 */

	protected $_Http = null;



	/**
	 *	Internal DOM parser.
	 *
	 *	@var Essence\Dom\Parser
	 */

	protected $_Dom = null;



	/**
	 *	### Options
	 *
	 *	- 'html' callable( array $og ) A function to build an HTML code from
	 *		the given OpenGraph properties.
	 */

	protected $_properties = array(
		'prepare' => 'OpenGraph::prepare',
		'html' => 'OpenGraph::html'
	);



	/**
	 *	Constructor.
	 *
	 *	@param Essence\Http\Client $Http Http client.
	 *	@param Essence\Dom\Parser $Cache Dom parser.
	 */

	public function __construct( HttpClient $Http, DomParser $Dom ) {

		$this->_Http = $Http;
		$this->_Dom = $Dom;
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
			Hash::reindex(
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

		$attributes = $this->_Dom->extractAttributes(
			$this->_Http->get( $url ),
			array(
				'meta' => array(
					'property' => '#^og:.+#i',
					'content'
				)
			)
		);

		$og = array( );

		if ( !empty( $attributes['meta'])) {
			foreach ( $attributes['meta'] as $meta ) {
				if ( !isset( $og[ $meta['property']])) {
					$og[ $meta['property']] = trim( $meta['content']);
				}
			}

			if ( empty( $og['html']) && is_callable( $this->_html )) {
				if ( empty( $og['og:url'])) {
					$og['og:url'] = $url;
				}

				$og['html'] = call_user_func( $this->_html, $og );
			}
		}

		return $og;
	}



	/**
	 *	Builds an HTML code from OpenGraph properties.
	 *
	 *	@param array $og OpenGraph properties.
	 *	@return string Generated HTML.
	 */

	public static function html( array $og ) {

		$og += array(
			'og:type' => 'unknown',
			'og:title' => $og['og:url']
		);

		$html = '';

		switch ( $og['og:type']) {

			// builds an <img> tag pointing to the photo
			case 'photo':
				$og += array(
					'og:width' => 500,
					'og:height' => 375
				);

				$html = sprintf(
					'<img src="%s" alt="%s" width="%d" height="%d" />',
					$og['og:url'],
					$og['og:title'],
					$og['og:width'],
					$og['og:height']
				);
				break;

			// builds an <iframe> tag pointing to the video player
			case 'video':
				$og += array(
					'og:video' => $og['og:url'],
					'og:video:width' => 560,
					'og:video:height' => 315
				);

				$html = sprintf(
					'<iframe src="%s" alt="%s" width="%d" height="%d" frameborder="0" allowfullscreen mozallowfullscreen webkitallowfullscreen></iframe>',
					$og['og:video'],
					$og['og:title'],
					$og['og:video:width'],
					$og['og:video:height']
				);
				break;

			// builds an <a> tag pointing to the original resource
			default:
				$og += array(
					'og:description' => $og['og:title']
				);

				$html = sprintf(
					'<a href="%s" alt="%s">%s</a>',
					$og['og:url'],
					$og['og:description'],
					$og['og:title']
				);
				break;
		}

		return $html;
	}
}

