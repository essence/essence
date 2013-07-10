<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (Dom://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Dom;

use Essence\Dom;
use Essence\Dom\Parser\Native;



/**
 *	Basic functionnalities for DOM parser consumers.
 *
 *	@package fg.Essence.Dom
 */

trait Consumer {

	/**
	 *	The internal DOM parser.
	 *
	 *	@var Essence\Dom
	 */

	protected $_Dom = null;



	/**
	 *	Sets the internal DOM parser.
	 *
	 *	@param Essence\Dom $Dom DOM parser.
	 */

	public function setDom( Dom &$Dom ) {

		$this->_Dom =& $Dom;
	}



	/**
	 *	Returns the internal DOM parser.
	 *
	 *	@return Essence\Dom DOM parser.
	 */

	public function &_dom( ) {

		if ( $this->_Dom === null ) {
			$this->_Dom = new Native( );
		}

		return $this->_Dom;
	}
}
