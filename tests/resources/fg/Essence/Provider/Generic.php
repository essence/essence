<?php

namespace fg\Essence\Provider;



/**
 *
 */

class Generic extends \fg\Essence\Provider {

	/**
	 *
	 */

	public function __construct( ) {

		parent::__construct( self::anything );
	}



	/**
	 *
	 */

	protected function _embed( $url ) { }

}
