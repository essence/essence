<?php

namespace fg\Essence\Provider;



/**
 *
 */

class Foo extends \fg\Essence\Provider {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#^foo$#';



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) { }

}
