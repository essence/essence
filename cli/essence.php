#!/usr/bin/php -q
<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 *	@todo Create a separate repo for CLI with clean code.
 */
namespace Essence;

use Exception;



/**
 *	Autoloading.
 */
require_once dirname(dirname(__FILE__))
	. DIRECTORY_SEPARATOR . 'vendor'
	. DIRECTORY_SEPARATOR . 'autoload.php';



/**
 *	Processing.
 */
$Cli = new Cli();
$Cli->process($argv);



/**
 *
 */
class Cli {

	/**
	 *
	 */
	protected $_Essence = null;



	/**
	 *
	 */
	public function __construct() {
		$this->_Essence = new Essence();
	}



	/**
	 *
	 */
	public function process(array $arguments) {
		try {
			$this->_parse($arguments);
		} catch (Exception $Exception) {
			$this->_print($Exception->getMessage());
		}
	}



	/**
	 *
	 */
	public function _parse(array $arguments) {
		// strips away the program name
		array_shift($arguments);

		if (count($arguments) < 2) {
			$arguments = ['help'];
		}

		switch ($arguments[0]) {
			case 'crawl':
				$this->_crawl($arguments[1]);
				break;

			case 'extract':
				$this->_extract($arguments[1]);
				break;

			case 'help':
			default:
				$this->_help();
				break;
		}
	}



	/**
	 *
	 */
	protected function _help() {
		$this->_print('crawl [URL]');
		$this->_print('	Crawls extractable URLs from a page.', 2);
		$this->_print('extract [URL]');
		$this->_print('	Extracts informations about a page.');
	}



	/**
	 *
	 */
	protected function _crawl($url) {
		$urls = $this->_Essence->crawlUrl($url);

		if (!$urls) {
			throw new Exception('No URL found.');
		}

		$this->_printArray($urls);
	}



	/**
	 *
	 */
	protected function _extract($url) {
		$Media = $this->_Essence->extract($url);
		$properties = $Media
			? $Media->filledProperties()
			: [];

		if (!$properties) {
			throw new Exception('No information found.');
		}

		$this->_printArray($properties);
	}



	/**
	 *
	 */
	protected function _printArray(array $data) {
		$length = $this->_maxKeyLength($data);

		foreach ($data as $key => $value) {
			$this->_print(sprintf(
				"%{$length}s: %s",
				$key,
				$value
			));
		}
	}



	/**
	 *
	 */
	protected function _maxKeyLength(array $data) {
		$keys = array_keys($data);
		$lengths = array_map('strlen', $keys);

		return max($lengths);
	}



	/**
	 *
	 */
	protected function _print($text, $lineBreaks = 1) {
		echo $text . str_repeat(PHP_EOL, $lineBreaks);
	}
}
