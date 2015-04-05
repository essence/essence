<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom;



/**
 *	Represents a DOM tag.
 */
abstract class Tag {

	/**
	 *	Returns the value of an attribute.
	 *
	 *	@param string $name Attribute name.
	 *	@param mixed $default Default value to be returned if the attribute
	 *		doesn't exist.
	 *	@return mixed Attribute value, or $default.
	 */
	abstract public function get($name, $default = '');



	/**
	 *	Tests if the value of an attribute matches the given pattern.
	 *
	 *	@param string $name Attribute name.
	 *	@param string $pattern Pattern.
	 *	@param array $matches Search results.
	 *	@return boolean If the attribute value matches the pattern.
	 */
	public function matches($name, $pattern, &$matches = []) {
		return preg_match($pattern, $this->get($name), $matches);
	}
}
