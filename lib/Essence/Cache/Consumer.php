<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Cache;

use Essence\Cache\Engine;
use Essence\Cache\Engine\Volatile;



/**
 *	Basic functionnalities for cache consumers.
 *
 *	@package fg.Essence.Cache
 */

trait Consumer {

	/**
	 *	The internal cache.
	 *
	 *	@var Essence\Cache
	 */

	protected $_Cache = null;



	/**
	 *	Sets the internal cache.
	 *
	 *	@param Essence\Cache\Engine $Engine Cache engine.
	 */

	public function setCache( Engine &$Engine ) {

		$this->_Cache =& $Engine;
	}



	/**
	 *	Returns the internal cache.
	 *
	 *	@return Essence\Cache Cache.
	 */

	public function &_cache( ) {

		if ( $this->_Cache === null ) {
			$this->_Cache = new Volatile( );
		}

		return $this->_Cache;
	}
}
