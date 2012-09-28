<?php

namespace fg\Essence\Provider;



/**
 *
 */

class Foo extends \fg\Essence\Provider {

	/**
	 *
	 */

	public function __construct( ) {

		parent::__construct( '#^foo$#' );
	}



	/**
	 *
	 */

	protected function _embed( $url ) { }

}
