<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Di\Container\Standard as StandardContainer;



/**
 *	A facade for the main functionnalities of Essence.
 */
class Essence {

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
		$Container = new StandardContainer($configuration);

		$this->_Http = $Container->get('Http');
		$this->_Crawler = $Container->get('Crawler');
		$this->_Extractor = $Container->get('Extractor');
		$this->_Replacer = $Container->get('Replacer');
	}



	/**
	 *	@see Essence\Crawler::crawl()
	 */
	public function crawl($source, $sourceUrl = '') {
		return $this->_Crawler->crawl($source, $sourceUrl);
	}



	/**
	 *	@see Essence\Crawler::crawl()
	 */
	public function crawlUrl($url) {
		return $this->_Crawler->crawl(
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
