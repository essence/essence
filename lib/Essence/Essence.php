<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Di\Container;
use Essence\Di\Container\Standard as StandardContainer;
use Essence\Utility\Url;



/**
 *	A facade for the main functionnalities of Essence.
 */
class Essence {

	/**
	 *	DI container.
	 *
	 *	@var Container
	 */
	protected $_Container = null;



	/**
	 *	HTTP client.
	 *
	 *	@var Http
	 */
	protected $_Http = null;



	/**
	 *	URL crawler.
	 *
	 *	@var Essence\Crawler
	 */
	protected $_Crawler = null;



	/**
	 *	Information extractor.
	 *
	 *	@var Essence\Extractor
	 */
	protected $_Extractor = null;



	/**
	 *	URL replacer.
	 *
	 *	@var Essence\Replacer
	 */
	protected $_Replacer = null;



	/**
	 *	Constructor.
	 *
	 *	@param array $configuration Dependency injection configuration.
	 */
	public function __construct(array $configuration = []) {
		$this->_Container = new StandardContainer($configuration);

		$this->_Http = $this->_Container->get('Http');
		$this->_Crawler = $this->_Container->get('Crawler');
		$this->_Extractor = $this->_Container->get('Extractor');
		$this->_Replacer = $this->_Container->get('Replacer');
	}



	/**
	 *	Returns the internal DI container.
	 *
	 *	@see https://github.com/felixgirault/essence/issues/80
	 *	@see https://github.com/felixgirault/essence/issues/82
	 *	@return Container Container.
	 */
	public function container() {
		return $this->_Container;
	}



	/**
	 *	Crawls the given source for extractable URLs, optionnaly
	 *	resolving them relatively to a base one.
	 *
	 *	@see Essence\Crawler::crawl()
	 *	@see Essence\Utility\Url::resolve()
	 *	@param string $source HTML source.
	 *	@param string $base Base URL.
	 *	@return array URLs.
	 */
	public function crawl($source, $base = '') {
		$urls = $this->_Crawler->crawl($source);

		return $base
			? Url::resolveAll($urls, $base)
			: $urls;
	}



	/**
	 *	@see crawl()
	 */
	public function crawlUrl($url) {
		return $this->crawl(
			$this->_Http->get($url),
			$url
		);
	}



	/**
	 *	@see Essence\Extractor::extract()
	 */
	public function extract($url, array $options = []) {
		return $this->_Extractor->extract($url, $options);
	}



	/**
	 *	@see Essence\Extractor::extractAll()
	 */
	public function extractAll(array $urls, array $options = []) {
		return $this->_Extractor->extractAll($urls, $options);
	}



	/**
	 *	@see Essence\Replacer::replace()
	 */
	public function replace($text, $template = null, array $options = []) {
		return $this->_Replacer->replace($text, $template, $options);
	}
}
