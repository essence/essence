<?php

namespace fg\Essence\Provider;

use fg\Essence\Provider;



/**
 *
 */

class Foo extends Provider {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#^foo$#';



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) { }

}
