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
	 *	@see Crawler::crawl()
	 */
	public function extract($source) {
		return $this->_Crawler->crawl($source);
	}



	/**
	 *	@see Extractor::extract()
	 */
	public function embed($url, array $options = []) {
		return $this->_Extractor->extract($url, $options);
	}



	/**
	 *	@see Extractor::extractAll()
	 */
	public function embedAll(array $urls, array $options = []) {
		return $this->_Extractor->extractAll($urls, $options);
	}



	/**
	 *	@see Replacer::replace()
	 */
	public function replace($text, $template = null, array $options = []) {
		return $this->_Replacer->replace($text, $template, $options);
	}
}
