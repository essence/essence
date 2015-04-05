<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Preparator;

use Essence\Provider\Preparator;
use Essence\Utility\Template;



/**
 *	Refactors unhandled URLs.
 */
class Refactorer extends Preparator {

	/**
	 *	Regex to extract an id from an URL.
	 *
	 *	@var string
	 */
	protected $_pattern = '';



	/**
	 *	Template to build an URL from an id.
	 *
	 *	@var string
	 */
	protected $_template = '';



	/**
	 *	Constructor.
	 *
	 *	@param string $idPattern Regex to extract an id from an URL.
	 *	@param string $urlTemplate Template to build an URL from an id.
	 */
	public function __construct($idPattern, $urlTemplate) {
		$this->_pattern = $idPattern;
		$this->_template = $urlTemplate;
	}



	/**
	 *	{@inheritDoc}
	 */
	public function prepare($url) {
		if (preg_match($this->_pattern, $url, $matches)) {
			$url = Template::compile($this->_template, [
				'id' => $matches['id']
			]);
		}

		return $url;
	}
}
