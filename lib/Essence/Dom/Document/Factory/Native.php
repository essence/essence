<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom\Document\Factory;

use Essence\Dom\Document\Factory;
use Essence\Dom\Document\Native as Document;



/**
 *
 */
class Native {

	/**
	 *
	 */
	public function document($html) {
		return new Document($html);
	}
}
