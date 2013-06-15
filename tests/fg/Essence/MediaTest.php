<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( dirname( __FILE__ )))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Media.
 */

class MediaTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $properties = array(
		'title' => 'Title',
		'description' => 'Description.',
		'width' => 800,
		'height' => 600,
		'custom' => 'Custom property'
	);



	/**
	 *
	 */

	public $Media = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Media = new Media( $this->properties );
	}



	/**
	 *
	 */

	public function testConstruct( ) {

		$this->assertEquals(
			$this->properties['title'],
			$this->Media->title
		);
	}



	/**
	 *
	 */

	public function testConstructCustom( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Media->custom
		);
	}



	/**
	 *
	 */

	public function testMagicIsSet( ) {

		$this->assertTrue( isset( $this->Media->custom ));
	}



	/**
	 *
	 */

	public function testMagicIsntSet( ) {

		$this->assertFalse( isset( $this->Media->unknown ));
	}



	/**
	 *
	 */

	public function testMagicGet( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Media->custom
		);
	}



	/**
	 *
	 */

	public function testMagicSet( ) {

		$this->Media->foo = 'bar';
		$this->assertEquals( 'bar', $this->Media->foo );
	}



	/**
	 *
	 */

	public function testHas( ) {

		$this->assertTrue( $this->Media->has( 'custom' ));
	}



	/**
	 *
	 */

	public function testHasnt( ) {

		$this->assertFalse( $this->Media->has( 'unknown' ));
	}



	/**
	 *
	 */

	public function testGet( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Media->get( 'custom' )
		);
	}



	/**
	 *
	 */

	public function testGetUnknown( ) {

		$this->assertNull( $this->Media->get( 'unknown' ));
	}



	/**
	 *
	 */

	public function testSet( ) {

		$this->Media->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $this->Media->foo );
	}
}
