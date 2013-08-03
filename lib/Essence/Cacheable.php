<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use Essence\Cache\Engine;



/**
 *	Allows an object to cache method calls.
 *
 *	@package fg.Essence
 */

trait Cacheable {

	/**
	 *	Internal cache engine.
	 *
	 *	@var Essence\Cache\Engine
	 */

	protected $_Cache = null;



	/**
	 *	Returns the cached result of a method call.
	 *
	 *	@param Essence\Cache\Engine $Engine Cache engine.
	 *	@param string $method The method to cache.
	 *	@param ... mixed Parameters to be passed to the method.
	 *	@return mixed Cached result.
	 */

	protected function _cached( $method ) {

		$signature = $method;
		$args = array( );

		if ( func_num_args( ) > 1 ) {
			$args = array_slice( func_get_args( ), 1 );
			$signature .= json_encode( $args );
		}

		$key = $this->_cacheKey( $signature );

		if ( $this->_Cache->has( $key )) {
			return $this->_Cache->get( $key );
		}

		$result = call_user_func_array( array( $this, $method ), $args );
		$this->_Cache->set( $key, $result );

		return $result;
	}



	/**
	 *	Generates a key from the given signature.
	 *
	 *	@param string $key Method signature.
	 *	@return string Generated key.
	 */

	protected function _cacheKey( $signature ) {

		return md5( $signature );
	}
}
