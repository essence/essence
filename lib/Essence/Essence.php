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
	 *	@var Essence\Crawler
	 */
	protected $_Crawler = null;



	/**
	 *	@var Essence\Extractor
	 */
	protected $_Extractor = null;



	/**
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

		$this->_Crawler = $Container->get('Crawler');
		$this->_Extractor = $Container->get('Extractor');
		$this->_Replacer = $Container->get('Replacer');
	}



	/**
	 *	@see Essence\Crawler::crawl()
	 */
	public function crawl($source) {
		return $this->_Crawler->crawl($source);
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
