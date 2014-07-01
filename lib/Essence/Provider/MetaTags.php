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



/**
 *	Extracts embed informations from meta tags.
 */

class MetaTags extends Provider {

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
	 *	- 'scheme' string Scheme.
	 */

	protected $_properties = [
		'scheme' => '#.+#'
	];



	/**
	 *	Constructor.
	 *
	 *	@param HttpClient $Http HTTP client.
	 *	@param DomParser $Dom DOM parser.
	 *	@param array $preparators Preparator.
	 *	@param array $presenters Presenters.
	 */

	public function __construct(
		HttpClient $Http,
		DomParser $Dom,
		array $preparators = [ ],
		array $presenters = [ ]
	) {
		$this->_Http = $Http;
		$this->_Dom = $Dom;

		parent::__construct( $preparators, $presenters );
	}



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, array $options ) {

		$html = $this->_Http->get( $url );
		$attributes = $this->_Dom->extractAttributes( $html, [
			'meta' => [
				'property' => $this->scheme,
				'content'
			]
		]);

		if ( empty( $attributes['meta'])) {
			throw new Exception(
				"Unable to extract MetaTags data from '$url'."
			);
		}

		$og = [ ];

		foreach ( $attributes['meta'] as $meta ) {
			if ( !isset( $og[ $meta['property']])) {
				$og[ $meta['property']] = trim( $meta['content']);
			}
		}

		return new Media( $og );
	}
}
