<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Dom;

use Essence\Dom\Parser;
use Essence\Dom\Parser\Native;



/**
 *	A DOM parser consumer.
 *
 *	@package fg.Essence.Dom
 */

trait Consumer {

	/**
	 *	The internal DOM parser.
	 *
	 *	@var Essence\Dom\Parser
	 */

	protected $_domParser = null;



	/**
	 *	Sets the internal DOM parser.
	 *
	 *	@param Essence\Dom\Parser $Parser DOM parser.
	 */

	public function setDomParser( Parser &$Parser ) {

		$this->_domParser =& $Parser;
	}



	/**
	 *	Returns the internal DOM parser.
	 *
	 *	@return Essence\Dom\Parser DOM parser.
	 */

	public function &_domParser( ) {

		if ( $this->_domParser === null ) {
			$this->_domParser = new Native( );
		}

		return $this->_domParser;
	}
}
