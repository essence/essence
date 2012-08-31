<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( __FILE__ )) . DIRECTORY_SEPARATOR . 'bootstrap.php';
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

		$Reflection = new \ReflectionClass( '\Essence\Media' );
		
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

	public function testMagicIsset( ) {

		$this->assertTrue( isset( $this->Media->custom ));
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

	public function testHasCustomProperty( ) {

		$this->assertTrue( $this->Media->hasCustomProperty( 'custom' ));
		$this->assertFalse( $this->Media->hasCustomProperty( 'unknown' ));
	}



	/**
	 *
	 */

	public function testGetCustomProperties( ) {

		/*$this->assertEquals(
			$this->properties,
			$this->Media->getCustomProperties( )
		);*/
	}



	/**
	 *
	 */

	public function testGetCustomProperty( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Media->getCustomProperty( 'custom' )
		);
	}



	/**
	 *
	 */

	public function testGetUnknownCustomProperty( ) {

		$this->assertNull( $this->Media->getCustomProperty( 'unknown' ));
	}



	/**
	 *
	 */

	public function testSetCustomProperty( ) {

		$this->Media->setCustomProperty( 'foo', 'bar' );
		$this->assertEquals( 'bar', $this->Media->foo );
	}
}