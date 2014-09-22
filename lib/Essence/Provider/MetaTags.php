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
	 *	HTTP client.
	 *
	 *	@var Http
	 */
	protected $_Http = null;



	/**
	 *	DOM parser.
	 *
	 *	@var Dom
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
	 */
	public function __construct(Http $Http, Dom $Dom) {
		parent::__construct();

		$this->_Http = $Http;
		$this->_Dom = $Dom;
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
		$metas = $this->_extractMetas($html);
		$Media = new Media();

		foreach ($metas as $meta) {
			$Media->set($meta['property'], trim($meta['content']));
		}

		return $Media;
	}



	/**
	 *	Extracts meta tags from the given HTML source.
	 *
	 *	@param string $html HTML.
	 *	@return array Meta tags.
	 */
	protected function _extractMetas($html) {
		$attributes = $this->_Dom->extractAttributes($html, [
			'meta' => [
				'property' => $this->_metaPattern,
				'content'
			]
		]);

		if (empty($attributes['meta'])) {
			throw new Exception("Unable to extract meta tags from '$url'.");
		}

		return $attributes['meta'];
	}
}
