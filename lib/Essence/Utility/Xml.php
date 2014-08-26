<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;

use Essence\Exception;
use Exception as NativeException;
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
		$Iterator = self::_iterator($xml);
		$data = [];

		foreach ($Iterator as $key => $value) {
			$data[$key] = strval($value);
		}

		libxml_use_internal_errors($internal);
		return $data;
	}



	/**
	 *	Builds and returns a SimpleXmlIterator.
	 *
	 *	@param string $xml XML document.
	 *	@return SimpleXmlIterator Iterator.
	 */
	protected static function _iterator($xml) {
		try {
			return new SimpleXmlIterator($xml);
		} catch (NativeException $Exception) {
			throw Exception::wrap($Exception);
		}
	}
}
