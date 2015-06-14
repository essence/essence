<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;

use SimpleXmlIterator;



/**
 *	A simple XML parser.
 */
class Xml {

	/**
	 *	Parses an XML document and returns an array of data.
	 *
	 *	@param string $xml XML document.
	 *	@return array Data.
	 */
	public static function parse($xml) {
		$internal = libxml_use_internal_errors(true);
		$Iterator = new SimpleXmlIterator($xml);
		$data = [];

		foreach ($Iterator as $key => $value) {
			$data[$key] = strval($value);
		}

		libxml_use_internal_errors($internal);
		return $data;
	}
}
