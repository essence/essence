<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Provider\Collection;
use Essence\Dom\Document\Factory\Native as Dom;
use Essence\Dom\Document;



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
	 *	DOM parser.
	 *
	 *	@var Dom
	 */
	protected $_Dom = null;



	/**
	 *
	 */
	protected $_attributes = [
		'a' => 'href',
		'embed' => 'src',
		'iframe' => 'src'
	];



	/**
	 *	Constructor.
	 *
	 *	@param Collection $Collection Providers collection.
	 *	@param Dom $Dom DOM parser.
	 */
	public function __construct(Collection $Collection, Dom $Dom) {
		$this->_Collection = $Collection;
		$this->_Dom = $Dom;
	}



	/**
	 *	Extracts embeddable URLs from an HTML source.
	 *
	 *	@param string $html The HTML source to be extracted.
	 *	@param string $url URL of the HTML source.
	 *	@return array An array of extracted URLs.
	 */
	public function crawl($html, $url = '') {
		$Document = $this->_Dom->document($html);
		$urls = $this->_extractUrls($Document);

		if ($url) {
			$urls = $this->_completeUrls($urls, $url);
		}

		return $this->_filterUrls(array_unique($urls));
	}



	/**
	 *	Extracts URLs from the given DOM document.
	 *
	 *	@param Document $Document Document.
	 *	@return array URLs.
	 */
	protected function _extractUrls(Document $Document) {
		$urls = [];

		foreach ($this->_attributes as $tag => $attribute) {
			$urls = array_merge(
				$this->_extractUrlsFromtags($Document, $tag, $attribute),
				$urls
			);
		}

		return $urls;
	}



	/**
	 *	Extracts URLs from tag attributes.
	 *
	 *	@param Document $Document Document.
	 *	@param string $tag Tag name.
	 *	@param string $attribute Attribute name.
	 *	@return array URLs.
	 */
	protected function _extractUrlsFromtags(Document $Document, $tag, $attribute) {
		$tags = $Document->tags($tag);

		return array_map(function($Tag) use ($attribute) {
			return $Tag->get($attribute);
		}, $tags);
	}



	/**
	 *	Completes relative URLs.
	 *
	 *	@param array $urls URLs to complete.
	 *	@param string $url URL of the page from which URLs were extracted.
	 *	@return array Completed URLs.
	 */
	protected function _completeUrls(array $urls, $url) {
		$components = parse_url($url);
		$scheme = isset($components['scheme'])
			? $components['scheme']
			: self::defaultScheme;

		$base = $scheme . '://' . $components['host'];

		return array_map(
			$this->_completeUrlFunction($scheme, $base),
			$urls
		);
	}



	/**
	 *	Returns a function used to complete relative URLs.
	 *
	 *	@see _completeUrls()
	 *	@param string $scheme Default scheme.
	 *	@param string $base Default base.
	 *	@return \Closure Function.
	 */
	protected function _completeUrlFunction($scheme, $base) {
		return function($url) use ($scheme, $base) {
			if (strpos($url, '//') === 0) {
				return $scheme . ':' . $url;
			}

			if (strpos($url, '/') === 0) {
				return $base . $url;
			}

			return $url;
		};
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
