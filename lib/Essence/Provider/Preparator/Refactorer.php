<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Preparator;

use Essence\Utility\Template;



/**
 *	Refactors unhandled URLs.
 */
class Refactorer {

	/**
	 *	Regex to extract an id from an URL.
	 *
	 *	@var string
	 */
	protected $_regex = '';



	/**
	 *	Template to build an URL from an id.
	 *
	 *	@var string
	 */
	protected $_template = '';



	/**
	 *	Constructor.
	 *
	 *	@param string $regex Regex to extract an id from an URL.
	 *	@param string $template Template to build an URL from an id.
	 */
	public function __construct($regex, $template) {
		$this->_regex = $regex;
		$this->_template = $template;
	}



	/**
	 *	{@inheritDoc}
	 */
	public function filter($url) {
		if (preg_match($this->_regex, $url, $matches)) {
			$url = Template::compile($this->_template, [
				'id' => $matches['id']
			]);
		}

		return $url;
	}
}
