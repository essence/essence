<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom;

use Closure;



/**
 *	Represents an HTML document.
 */
abstract class Document {

	/**
	 *	HTML source.
	 *
	 *	@var string
	 */
	protected $_html;



	/**
	 *	Contructor.
	 *
	 *	@param string $html HTML source.
	 */
	public function __construct($html) {
		$this->_html = $html;
	}



	/**
	 *	Returns all tags identified by the given name.
	 *
	 *	@param string $name Tag name.
	 *	@param Closure $filter A callback to filter tags.
	 *	@return array Tags.
	 */
	abstract public function tags($name, Closure $filter = null);

}
