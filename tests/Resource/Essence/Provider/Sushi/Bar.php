<?php

namespace Essence\Provider\Sushi;



/**
 *
 */

class Bar extends \Essence\Provider {

	/**
	 *
	 */
	
	public function __construct( ) {

		parent::__construct( '#^bar$#' );
	}



	/**
	 *
	 */

	protected function _embed( $url ) {

	}
}