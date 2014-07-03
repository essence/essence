<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Media.
 */

class MediaTest extends TestCase {

	/**
	 *
	 */

	public $Media = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Media = new Media( [
			'property' => 'value'
		]);
	}



	/**
	 *
	 */

	public function testConstruct( ) {

		$this->assertTrue( $this->Media->has( 'property' ));
	}



	/**
	 *
	 */

	public function testIterator( ) {

		$properties = [ ];

		foreach ( $this->Media as $property => $value ) {
			$properties[ $property ] = $value;
		}

		$this->assertEquals( $properties, $this->Media->properties( ));
	}



	/**
	 *
	 */

	public function testSerialize( ) {

		$this->assertEquals(
			json_encode( $this->Media->properties( )),
			json_encode( $this->Media )
		);
	}
}
