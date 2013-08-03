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
use Essence\Log\Logger;
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
	 *	Constructor.
	 *
	 *	@param Essence\Http\Client $Http HTTP client.
	 *	@param Essence\Dom\Parser $Dom DOM parser.
	 *	@param Essence\Log\Logger $Log Logger.
	 */

	public function __construct(
		HttpClient $Http,
		DomParser $Dom,
		Logger $Log = null
	) {
		$this->_Http = $Http;
		$this->_Dom = $Dom;

		parent::__construct( $Log );
	}



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) {

		return new Media(
			Hash::reindex(
				$this->_extractInformations( $url ),
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

		if ( empty( $attributes['meta'])) {
			throw new Exception(
				"Unable to extract OpenGraph data from '$url'."
			);
		} else {
			foreach ( $attributes['meta'] as $meta ) {
				if ( !isset( $og[ $meta['property']])) {
					$og[ $meta['property']] = trim( $meta['content']);
				}
			}
		}

		return $og;
	}
}

