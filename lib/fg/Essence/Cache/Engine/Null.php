<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Cache\Engine;

use fg\Essence\Cache\Engine;



/**
 *	Does absolutely nothing.
 *
 *	@package fg.Essence.Cache.Engine
 */

class Null implements Engine {

	/**
	 *	{@inheritDoc}
	 */

	public function has( $key ) {

		return false;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function get( $key, $default = null ) {

		return $default;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function set( $key, $data ) {

		return $data;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function clear( ) { }

}
