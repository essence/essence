<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Provider\Collection;
use Essence\Http\Client as Http;
use Essence\Dom\Document\Factory\Native as Dom;



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
		$html = $this->_html($source);
		$Document = $this->_Dom->document($html);

		$urls = array_merge(
			$this->_extractUrls($Document, 'a', 'href'),
			$this->_extractUrls($Document, 'embed', 'src'),
			$this->_extractUrls($Document, 'iframe', 'src')
		);

		return $this->_filterUrls(array_unique($urls));
	}



	/**
	 *	If the given source is an URL, returns the page it points to.
	 *
	 *	@param string $source URL or HTML source.
	 *	@return string HTML source.
	 */
	public function _html($source) {
		return filter_var($source, FILTER_VALIDATE_URL)
			? $this->_Http->get($source)
			: $source;
	}



	/**
	 *	Extracts URLs from tag attributes.
	 *
	 *	@param Document $Document Document.
	 *	@param string $tag Tag name.
	 *	@param string $attribute Attribute name.
	 *	@return array URLs.
	 */
	protected function _extractUrls($Document, $tag, $attribute) {
		$tags = $Document->tags($tag);

		return array_map(function($Tag) use ($attribute) {
			return $Tag->get($attribute);
		}, $tags);
	}



	/**
	 *	Filters the given URLs to return only the extractable ones.
	 *
	 *	@param array $urls URLs to filter.
	 *	@return array Filtered URLs.
	 */
	protected function _filterUrls(array $urls) {
		$urls = array_filter($urls, function($url) {
			return $this->_Collection->hasProvider($url);
		});

		return array_values($urls);
	}
}
