<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom\Parser;

use Essence\Dom\Parser;
use Essence\Exception;
use Essence\Utility\Hash;
use DomDocument;
use DomNodeList;
use DomNode;



/**
 *	Handles HTML related operations through DomDocument.
 */
class Native implements Parser {

	/**
	 *	A pattern that matches anything.
	 *
	 *	@var string
	 */
	const anything = '~.+~';



	/**
	 *	{@inheritDoc}
	 */
	public function extractAttributes($html, array $options) {
		$Document = $this->_document($html);
		$options = Hash::normalize($options, []);
		$data = [];

		foreach ($options as $name => $required) {
			$data[$name] = $this->_extractAttributesFromTags(
				$Document->getElementsByTagName($name),
				Hash::normalize((array)$required, self::anything)
			);
		}

		return $data;
	}



	/**
	 *	Builds and returns a DomDocument from the given HTML source.
	 *
	 *	@param string $html HTML source.
	 *	@return DomDocument DomDocument.
	 */
	protected function _document($html) {
		$reporting = error_reporting(0);
		$Document = DomDocument::loadHTML($this->_fixCharset($html));
		error_reporting($reporting);

		if ($Document === false) {
			throw new Exception('Unable to load HTML document.');
		}

		return $Document;
	}



	/**
	 *	If necessary, fixes the given HTML's charset to work with the current
	 *	version of Libxml (used by DomDocument).
	 *	Older versions of Libxml only recognizes:
	 *		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	 *	and not the new HTML5 form:
	 *		<meta charset="utf-8">
	 *	with the result that parsed strings can have funny characters.
	 *
	 *	@see http://www.glenscott.co.uk/blog/html5-character-encodings-and-domdocument-loadhtml-and-loadhtmlfile
	 *	@see https://github.com/glenscott/dom-document-charset/blob/master/DOMDocumentCharset.php
	 *	@param string $html HTML source.
	 *	@return string Fixed HTML source.
	 */
	protected function _fixCharset($html) {
		if (LIBXML_VERSION < 20800 && stripos($html, 'meta charset') !== false) {
			$html = preg_replace(
				'/<meta charset=["\']?([^"\']+)"/i',
				'<meta http-equiv="Content-Type" content="text/html; charset=$1"',
				$html
			);
		}

		return $html;
	}



	/**
	 *	Extracts attributes from the given tags.
	 *
	 *	@param DOMNodeList $Tags Tags to extract attributes from.
	 *	@param array $required Required attributes.
	 *	@return array Extracted attributes.
	 */
	protected function _extractAttributesFromTags(DOMNodeList $Tags, array $required) {
		$data = [];

		foreach ($Tags as $Tag) {
			if ($Tag->hasAttributes()) {
				$attributes = $this->_extractAttributesFromTag($Tag, $required);
				$diff = array_diff_key($required, $attributes);

				if (empty($diff)) {
					$data[] = $attributes;
				}
			}
		}

		return $data;
	}



	/**
	 *	Extracts attributes from the given tag.
	 *
	 *	@param DOMNode $Tag Tag to extract attributes from.
	 *	@param array $required Required attributes.
	 *	@return array Extracted attributes.
	 */
	protected function _extractAttributesFromTag(DOMNode $Tag, array $required) {
		$attributes = [];

		foreach ($Tag->attributes as $name => $Attribute) {
			if (!empty($required)) {
				if (!isset($required[$name])) {
					continue;
				}

				if (!preg_match($required[$name], $Attribute->value)) {
					return [];
				}
			}

			$attributes[$name] = $Attribute->value;
		}

		return $attributes;
	}
}
