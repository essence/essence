<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Cache;



/**
 *	Handles caching for a single session.
 *
 *	@package fg.Essence.Cache
 */

class Volatile implements \fg\Essence\Cache {

	/**
	 *	Data.
	 *
	 *	@var array
	 */

	protected $_data = array( );



	/**
	 *	{@inheritDoc}
	 */

	public function has( $key ) {

		return array_key_exists( $key, $this->_data );
	}



	/**
	 *	{@inheritDoc}
	 */

	public function get( $key, $default = null ) {

		if ( $this->has( $key )) {
			return $this->_data[ $key ];
		}

		return $default;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function set( $key, $data ) {

		$this->_data[ $key ] = $data;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function clear( ) {

		$this->_data = array( );
	}
}
