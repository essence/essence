<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Cache;



/**
 *	Does absolutely nothing.
 *
 *	@package fg.Essence.Cache
 */

class Null implements \fg\Essence\Cache {

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

	public function set( $key, $data ) { }



	/**
	 *	{@inheritDoc}
	 */

	public function clear( ) { }

}
