<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Cache;

use Essence\Cache\Engine;
use Essence\Cache\Engine\Volatile;



/**
 *	A cache engine consumer.
 *
 *	@package fg.Essence.Cache
 */

trait Consumer {

	/**
	 *	The internal cache engine.
	 *
	 *	@var Essence\Cache\Engine
	 */

	protected $_cacheEngine = null;



	/**
	 *	Sets the internal cache engine.
	 *
	 *	@param Essence\Cache\Engine $Engine Cache engine.
	 */

	public function setCacheEngine( Engine &$Engine ) {

		$this->_cacheEngine =& $Engine;
	}



	/**
	 *	Returns the internal cache engine.
	 *
	 *	@return Essence\Cache\Engine Cache.
	 */

	public function &_cacheEngine( ) {

		if ( $this->_cacheEngine === null ) {
			$this->_cacheEngine = new Volatile( );
		}

		return $this->_cacheEngine;
	}
}
