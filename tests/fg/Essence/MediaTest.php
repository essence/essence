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

	public function testReindex( ) {

		$Media = new Media(
			$this->properties,
			array( 'title' => 'title2' )
		);

		$this->assertEquals(
			$this->properties['title'],
			$Media->title2
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

	public function testHasProperty( ) {

		$this->assertTrue( $this->Media->hasProperty( 'custom' ));
	}



	/**
	 *
	 */

	public function testHasntProperty( ) {

		$this->assertFalse( $this->Media->hasProperty( 'unknown' ));
	}



	/**
	 *
	 */

	public function testProperties( ) {

		$this->assertEquals(
			$this->properties,
			$this->Media->properties( )
		);
	}



	/**
	 *
	 */

	public function testProperty( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Media->property( 'custom' )
		);
	}



	/**
	 *
	 */

	public function testGetUnknownProperty( ) {

		$this->assertNull( $this->Media->property( 'unknown' ));
	}



	/**
	 *
	 */

	public function testSetProperty( ) {

		$this->Media->setProperty( 'foo', 'bar' );
		$this->assertEquals( 'bar', $this->Media->foo );
	}
}
