<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;

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
 *	Test case for Filters.
 */

class FiltersTest extends TestCase {

	/**
	 *
	 */

	public $Filters = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Filters = new Filters([
			new FooFilter( ),
			new BarFilter( )
		]);
	}



	/**
	 *
	 */

	public function testFilter( ) {

		$this->assertEquals( 4, $this->Filters->filter( 1 ));
	}
}
