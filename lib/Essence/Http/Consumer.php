<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Http;

use Essence\Http;
use Essence\Http\Client\Native;



/**
 *	An HTTP client consumer.
 *
 *	@package fg.Essence.Http
 */

trait Consumer {

	/**
	 *	The internal HTTP client.
	 *
	 *	@var Essence\Http\Client
	 */

	protected $_httpClient = null;



	/**
	 *	Sets the internal HTTP client.
	 *
	 *	@param Essence\Http\Client $Client HTTP client.
	 */

	public function setHttpClient( Client &$Client ) {

		$this->_httpClient =& $Client;
	}



	/**
	 *	Returns the internal HTTP client.
	 *
	 *	@return Essence\Http\Client HTTP client.
	 */

	public function &_httpClient( ) {

		if ( $this->_httpClient === null ) {
			$this->_httpClient = new Native( );
		}

		return $this->_httpClient;
	}
}
