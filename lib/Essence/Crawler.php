<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Provider\Collection;
use Essence\Http\Client as Http;
use Essence\Dom\Parser as Dom;



/**
 *	Extracts embeddable URLs from web pages.
 */
class Crawler {

	/**
	 *	A collection of providers.
	 *
	 *	@var Collection
	 */
	protected $_Collection = null;



	/**
	 *	Internal HTTP client.
	 *
	 *	@var Http
	 */
	protected $_Http = null;



	/**
	 *	Internal DOM parser.
	 *
	 *	@var Dom
	 */
	protected $_Dom = null;



	/**
	 *	Constructor.
	 *
	 *	@param Collection $Collection Providers collection.
	 *	@param Http $Http HTTP client.
	 *	@param Dom $Dom DOM parser.
	 */
	public function __construct(Collection $Collection, Http $Http, Dom $Dom) {
		$this->_Collection = $Collection;
		$this->_Http = $Http;
		$this->_Dom = $Dom;
	}



	/**
	 *	Extracts embeddable URLs from either an URL or an HTML source.
	 *
	 *	@param string $source The URL or HTML source to be extracted.
	 *	@return array An array of extracted URLs.
	 */
	public function crawl($source) {
		if (filter_var($source, FILTER_VALIDATE_URL)) {
			$source = $this->_Http->get($source);
		}

		return $this->_filterUrls(
			$this->_extractUrls($source)
		);
	}



	/**
	 *	Extracts URLs from an HTML source.
	 *
	 *	@param string $html The HTML source to extract URLs from.
	 *	@return array Extracted URLs.
	 */
	protected function _extractUrls($html) {
		$options = [
			'a' => 'href',
			'embed' => 'src',
			'iframe' => 'src'
		];

		$attributes = $this->_Dom->extractAttributes($html, $options);
		$urls = [];

		foreach ($options as $tagName => $attributeName) {
			foreach ($attributes[$tagName] as $tag) {
				$url = $tag[$attributeName];

				if ($this->_Collection->hasProvider($url)) {
					$urls[] = $url;
				}
			}
		}

		return array_unique($urls);
	}



	/**
	 *
	 */
	protected function _filterUrls(array $urls) {
		$urls = array_filter($urls, function($url) {
			return $this->_Collection->hasProvider($url);
		});

		return array_values($urls);
	}
}
