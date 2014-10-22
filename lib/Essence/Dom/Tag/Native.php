<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom\Tag;

use Essence\Dom\Tag;
use DOMElement;



/**
 *	Wraps a DOMElement object.
 */
class Native extends Tag {

	/**
	 *	DOM element.
	 *
	 *	@var DOMElement
	 */
	protected $_Element = null;



	/**
	 *	Constructeur.
	 *
	 *	@param DOMElement $Element DOM element.
	 */
	public function __construct(DOMElement $Element) {
		$this->_Element = $Element;
	}



	/**
	 *	{@inheritDoc}
	 */
	public function get($name, $default = null) {
		return $this->_Element->hasAttribute($name)
			? $this->_Element->getAttribute($name)
			: $default;
	}
}
