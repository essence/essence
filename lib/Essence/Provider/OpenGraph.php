<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@author Laughingwithu <laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;

use Essence\Exception;
use Essence\Media;
use Essence\Media\Preparator;
use Essence\Provider;
use Essence\Dom\Parser as DomParser;
use Essence\Http\Client as HttpClient;
use Essence\Utility\Hash;



/**
 *	Base class for an OpenGraph provider.
 *	This kind of provider extracts embed informations from OpenGraph meta tags.
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

		$attributes = $this->_Dom->extractAttributes( $this->_Http->get( $url ), [
			'meta' => [
				'property' => '#^og:.+#i',
				'content'
			]
		]);

		$og = [ ];

		if ( empty( $attributes['meta'])) {
			throw new Exception(
				"Unable to extract OpenGraph data from '$url'."
			);
		}

		foreach ( $attributes['meta'] as $meta ) {
			if ( !isset( $og[ $meta['property']])) {
				$og[ $meta['property']] = trim( $meta['content']);
			}
		}

		return new Media( $og );
	}
}
