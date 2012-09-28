<?php

namespace fg\Essence\Provider\Sushi;



/**
 *
 */

class Bar extends \fg\Essence\Provider {

	/**
	 *
	 */

	public function __construct( ) {

		parent::__construct( '#^bar$#' );
	}



	/**
	 *
	 */

	protected function _embed( $url ) { }

}
