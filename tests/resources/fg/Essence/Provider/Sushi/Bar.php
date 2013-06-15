<?php

namespace fg\Essence\Provider\Sushi;

use fg\Essence\Provider;



/**
 *
 */

class Bar extends Provider {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#^bar$#';



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) { }

}
