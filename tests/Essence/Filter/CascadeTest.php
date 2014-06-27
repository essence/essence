<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Filter;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *
 */

class FooFilter {

	/**
	 *
	 */

	public function filter( $value ) {
		return $value + 1;
	}
}



/**
 *
 */

class BarFilter {

	/**
	 *
	 */

	public function filter( $value ) {

		return $value + 2;
	}
}



/**
 *	Test case for Cascade.
 */

class CascadeTest extends TestCase {

	/**
	 *
	 */

	public $Cascade = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Cascade = new Cascade([
			new FooFilter( ),
			new BarFilter( )
		]);
	}



	/**
	 *
	 */

	public function testFilter( ) {

		$this->assertEquals( 4, $this->Cascade->filter( 1 ));
	}
}
