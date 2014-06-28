<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;



/**
 *	Collection of filters.
 */

class Filters {

	/**
	 *	An array of filters.
	 *
	 *	@var array
	 */

	protected $_filters = [ ];



	/**
	 *	Constructor.
	 *
	 *	@param array $filters An array of filter objects, i.e. object that
	 *		must implement a filter() method.
	 */

	public function __construct( array $filters = [ ]) {

		$this->_filters = $filters;
	}



	/**
	 *	Filters a value.
	 *
	 *	@param mixed $value Value to filter.
	 *	@return mixed Filtered value.
	 */

	public function filter( $value ) {

		foreach ( $this->_filters as $Filter ) {
			$value = $Filter->filter( $value );
		}

		return $value;
	}
}
