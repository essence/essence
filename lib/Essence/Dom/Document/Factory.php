<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom\Document;

use Essence\Dom\Document;



/**
 *	A factory for documents.
 */
interface Factory {

	/**
	 *	Builds and returns a document.
	 *
	 *	@param string $html HTML source.
	 *	@return Document Document.
	 */
	public function document($html);

}
