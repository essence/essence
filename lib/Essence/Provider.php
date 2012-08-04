<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence;



/**
 *
 */

abstract class Provider {

	/**
	 *
	 */

	protected $_pattern = '#(?=a)b#';	// matches nothing by default



	/**
	 *
	 */

	public function __construct( $pattern ) {

		$this->_pattern = $pattern;
	}



	/**
	 *
	 */

	public function canFetch( $url ) {

		return preg_match( $this->_pattern, $url );
	}



	/**
	 *
	 */

	public final function fetch( $url ) {

		$url = $this->_prepare( trim( $url ));
		$data = $this->_fetch( $url );

		if ( is_array( $data ) && !isset( $data['url'])) {
			$data['url'] = $url;
		}

		return $data;
	}



	/**
	 *	Prepares an Url before fetching its contents. This method can be used
	 *	in subclasses to 
	 */

	protected function _prepare( $url ) {

		return $url;
	}



	/**
	 *
	 */

	protected function _fetch( $url ) {
		
		return array( );
	}

}
