<?php

namespace Essence\Provider;



/**
 *
 */

class Foo extends \Essence\Provider {

	/**
	 *
	 */
	
	public function __construct( ) {

		parent::__construct( '#^foo$#' );
	}



	/**
	 *
	 */

	protected function _fetch( $url ) {

	}
}