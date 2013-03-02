<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	Handles HTML related operations.
 *
 *	@package fg.Essence
 */

interface Dom {

	/**
	 *	Extracts tags attributes from the given HTML document.
	 *
	 *	@param string $html An HTML document.
	 *	@param array $options Options defining which attributes to extract.
	 *	@return array Extracted attributes indexed by tag name.
	 */

	public function extractAttributes( $html, array $options );

}
