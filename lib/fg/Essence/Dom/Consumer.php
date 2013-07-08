<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (Dom://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Dom;

use fg\Essence\Dom;
use fg\Essence\Dom\Native;



/**
 *
 *
 *	@package fg.Essence.Dom
 */

trait Consumer {

	/**
	 *	The internal DOM parser.
	 *
	 *	@var fg\Essence\Dom
	 */

	protected $_Dom = null;



	/**
	 *	Sets the internal DOM parser.
	 *
	 *	@param fg\Essence\Dom $Dom DOM parser.
	 */

	public function setDom( Dom &$Dom ) {

		$this->_Dom =& $Dom;
	}



	/**
	 *	Returns the internal DOM parser.
	 *
	 *	@return fg\Essence\Dom DOM parser.
	 */

	public function &_Dom( ) {

		if ( $this->_Dom === null ) {
			$this->_Dom = new Native( );
		}

		return $this->_Dom;
	}
}
