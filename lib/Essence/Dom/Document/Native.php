<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom\Document;

use Essence\Dom\Document;
use Essence\Dom\Tag\Native as Tag;
use Essence\Exception;
use DOMDocument;
use Closure;



/**
 *	Handles HTML related operations through DOMDocument.
 */
class Native extends Document {

	/**
	 *	DOM document.
	 *
	 *	@var DOMDocument
	 */
	protected $_Document = null;



	/**
	 *	{@inheritDoc}
	 */
	public function __construct($html) {
		parent::__construct($html);

		$this->_fixCharset();
		$this->_loadDocument();
	}



	/**
	 *	If necessary, fixes the given HTML's charset to work with the current
	 *	version of Libxml (used by DOMDocument).
	 *	Older versions of Libxml only recognizes:
	 *		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	 *	and not the new HTML5 form:
	 *		<meta charset="utf-8">
	 *	with the result that parsed strings can have funny characters.
	 *
	 *	@see http://www.glenscott.co.uk/blog/html5-character-encodings-and-domdocument-loadhtml-and-loadhtmlfile
	 *	@see https://github.com/glenscott/dom-document-charset/blob/master/DOMDocumentCharset.php
	 */
	protected function _fixCharset() {
		if (LIBXML_VERSION < 20800 && stripos($this->_html, 'meta charset') !== false) {
			$this->_html = preg_replace(
				'~<meta charset=["\']?([^"\']+)"~i',
				'<meta http-equiv="Content-Type" content="text/html; charset=$1"',
				$this->_html
			);
		}
	}



	/**
	 *	Builds a DOMDocument from the HTML source.
	 */
	protected function _loadDocument() {
		$this->_Document = new DOMDocument();

		$reporting = error_reporting(0);
		$loaded = $this->_Document->loadHTML($this->_html);
		error_reporting($reporting);

		if (!$loaded) {
			throw new Exception('Unable to load HTML document.');
		}
	}



	/**
	 *	{@inheritDoc}
	 */
	public function tags($name, Closure $filter = null) {
		$Elements = $this->_Document->getElementsByTagName($name);
		$tags = [];

		foreach ($Elements as $Element) {
			$Tag = new Tag($Element);

			if (!$filter || $filter($Tag)) {
				$tags[] = $Tag;
			}
		}

		return $tags;
	}
}
