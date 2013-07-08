<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Cache;

use fg\Essence\Cache;



/**
 *	.
 *
 *	@package fg.Essence.Cache
 */

trait Consumer {

	/**
	 *
	 */

	protected $_Cache = null;



	/**
	 *
	 */

	public function setCache( Cache &$Cache ) {

		$this->_Cache = $Cache;
	}



	/**
	 *
	 */

	protected function _cached( $callback ) {

		list(, $caller ) = debug_backtrace( false );

		$key = $caller['function'] . json_encode( $caller['args']);

		return $this->_Cache->has( $key )
			? $this->_Cache->get( $key )
			: $this->_Cache->set(
				$key,
				call_user_func_array( $callback, $caller['args'])
			);
	}
}
