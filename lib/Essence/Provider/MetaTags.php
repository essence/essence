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
use Essence\Dom\Parser as Dom;
use Essence\Http\Client as Http;



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
	 *	A regex to filter meta tags.
	 *
	 *	@var string
	 */
	protected $_metaPattern = '~.+~';



	/**
	 *	Constructor.
	 *
	 *	@param Http $Http HTTP client.
	 *	@param Dom $Dom DOM parser.
	 *	@param array $preparators Preparator.
	 *	@param array $presenters Presenters.
	 */
	public function __construct(
		Http $Http,
		Dom $Dom,
		array $preparators = [],
		array $presenters = []
	) {
		$this->_Http = $Http;
		$this->_Dom = $Dom;

		parent::__construct($preparators, $presenters);
	}



	/**
	 *	Sets the filter pattern.
	 *
	 *	@param string $pattern Pattern.
	 */
	public function setMetaPattern($pattern) {
		$this->_metaPattern = $pattern;
	}



	/**
	 *	{@inheritDoc}
	 */
	protected function _embed($url, array $options) {
		$html = $this->_Http->get($url);
		$attributes = $this->_Dom->extractAttributes($html, [
			'meta' => [
				'property' => $this->_metaPattern,
				'content'
			]
		]);

		if (empty($attributes['meta'])) {
			throw new Exception(
				"Unable to extract MetaTags data from '$url'."
			);
		}

		$og = [];

		foreach ($attributes['meta'] as $meta) {
			if (!isset($og[$meta['property']])) {
				$og[$meta['property']] = trim($meta['content']);
			}
		}

		return new Media($og);
	}
}
