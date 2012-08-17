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
 *	Test case for Embed.
 */

class EmbedTest extends \PHPUnit_Framework_TestCase {

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

	public $Embed = null;



	/**
	 *
	 */

	public function setUp( ) {

		$Reflection = new \ReflectionClass( '\Essence\Embed' );
		
		$this->Embed = new Embed( $this->properties );
	}



	/**
	 *
	 */

	public function testConstruct( ) {

		$this->assertEquals(
			$this->properties['title'],
			$this->Embed->title
		);
	}



	/**
	 *
	 */

	public function testConstructCustom( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Embed->custom
		);
	}



	/**
	 *
	 */

	public function testReindex( ) {

		$Embed = new Embed(
			$this->properties,
			array( 'title' => 'title2' )
		);
		
		$this->assertEquals(
			$this->properties['title'],
			$Embed->title2
		);
	}



	/**
	 *
	 */

	public function testMagicIsset( ) {

		$this->assertTrue( isset( $this->Embed->custom ));
		$this->assertFalse( isset( $this->Embed->unknown ));
	}



	/**
	 *
	 */

	public function testMagicGet( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Embed->custom
		);
	}



	/**
	 *
	 */

	public function testMagicSet( ) {

		$this->Embed->foo = 'bar';
		$this->assertEquals( 'bar', $this->Embed->foo );
	}



	/**
	 *
	 */

	public function testHasCustomProperty( ) {

		$this->assertTrue( $this->Embed->hasCustomProperty( 'custom' ));
		$this->assertFalse( $this->Embed->hasCustomProperty( 'unknown' ));
	}



	/**
	 *
	 */

	public function testGetCustomProperty( ) {

		$this->assertEquals(
			$this->properties['custom'],
			$this->Embed->getCustomProperty( 'custom' )
		);
	}



	/**
	 *
	 */

	public function testGetUnknownCustomProperty( ) {

		$this->assertNull( $this->Embed->getCustomProperty( 'unknown' ));
	}



	/**
	 *
	 */

	public function testSetCustomProperty( ) {

		$this->Embed->setCustomProperty( 'foo', 'bar' );
		$this->assertEquals( 'bar', $this->Embed->foo );
	}
}