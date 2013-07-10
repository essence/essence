<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Http;

use Essence\Http;
use Essence\Http\Client\Native;



/**
 *	Basic functionnalities for HTTP client consumers.
 *
 *	@package fg.Essence.Http
 */

trait Consumer {

	/**
	 *	The internal HTTP client.
	 *
	 *	@var Essence\Http
	 */

	protected $_Http = null;



	/**
	 *	Sets the internal HTTP client.
	 *
	 *	@param Essence\Http $Http HTTP client.
	 */

	public function setHttp( Http &$Http ) {

		$this->_Http =& $Http;
	}



	/**
	 *	Returns the internal HTTP client.
	 *
	 *	@return Essence\Http HTTP client.
	 */

	public function &_http( ) {

		if ( $this->_Http === null ) {
			$this->_Http = new Native( );
		}

		return $this->_Http;
	}
}
