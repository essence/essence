<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Cache;

use fg\Essence\Cache;
use fg\Essence\Cache\Volatile;



/**
 *	Basic functionnalities for cache consumers.
 *
 *	@package fg.Essence.Cache
 */

trait Consumer {

	/**
	 *	The internal cache.
	 *
	 *	@var fg\Essence\Cache
	 */

	protected $_Cache = null;



	/**
	 *	Sets the internal cache.
	 *
	 *	@param fg\Essence\Cache $Cache Cache.
	 */

	public function setCache( Cache &$Cache ) {

		$this->_Cache =& $Cache;
	}



	/**
	 *	Returns the internal cache.
	 *
	 *	@return fg\Essence\Cache Cache.
	 */

	public function &_cache( ) {

		if ( $this->_Cache === null ) {
			$this->_Cache = new Volatile( );
		}

		return $this->_Cache;
	}
}
