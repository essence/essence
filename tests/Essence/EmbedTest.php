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

	public $baseProperties = array( );



	/**
	 *
	 */

	public $customProperties = array(
		'title' => 'Title',
		'description' => 'Description.',
		'width' => 800,
		'height' => 600,
		'custom' => 'Custom property'
	);



	/**
	 *
	 */

	public $expectedProperties = array( );



	/**
	 *
	 */

	public $Embed = null;



	/**
	 *
	 */

	public function setUp( ) {

		$Reflection = new \ReflectionClass( '\Essence\Embed' );
		$defaults = $Reflection->getDefaultProperties( );

		$this->baseProperties = $defaults['_data'];
		$this->expectedProperties = array_merge(
			$this->baseProperties,
			$this->customProperties
		);
		
		$this->Embed = new Embed( $this->customProperties );
	}



	/**
	 *
	 */

	public function testConstruct( ) {

		$Reflection = new \ReflectionClass( '\Essence\Embed' );
		$property = $Reflection->getProperty( '_data' );
		$property->setAccessible( true );

		$this->assertEquals(
			$this->expectedProperties,
			$property->getValue( $this->Embed )
		);
	}



	/**
	 *
	 */

	public function testReindex( ) {

		$Embed = new Embed(
			$this->customProperties,
			array( 'title' => 'title2' )
		);
		
		$this->assertEquals(
			$this->customProperties['title'],
			$Embed->get( 'title2' )
		);
	}



	/**
	 *
	 */

	public function testMagicIsset( ) {

		$this->assertTrue( isset( $this->Embed->title ));
		$this->assertFalse( isset( $this->Embed->unknown ));
	}



	/**
	 *
	 */

	public function testMagicGet( ) {

		$this->assertEquals(
			$this->customProperties['title'],
			$this->Embed->title
		);
	}



	/**
	 *
	 */

	public function testMagicSet( ) {

		$this->Embed->foo = 'bar';
		$this->assertEquals( 'bar', $this->Embed->get( 'foo' ));
	}



	/**
	 *
	 */

	public function testHas( ) {

		$this->assertTrue( $this->Embed->has( 'title' ));
		$this->assertFalse( $this->Embed->has( 'unknown' ));
	}



	/**
	 *
	 */

	public function testGet( ) {

		$this->assertEquals(
			$this->customProperties['title'],
			$this->Embed->get( 'title' )
		);
	}



	/**
	 *
	 */

	public function testGetAll( ) {

		$this->assertEquals( $this->expectedProperties, $this->Embed->get( ));
	}



	/**
	 *
	 */

	public function testGetUnknown( ) {

		$this->assertNull( $this->Embed->get( 'unknown' ));
	}



	/**
	 *
	 */

	public function testSet( ) {

		$this->Embed->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $this->Embed->get( 'foo' ));
	}



	/**
	 *
	 */

	public function testSetMultiple( ) {

		$this->Embed->set( array( 'bar' => 'foo' ));
		$this->assertEquals( 'foo', $this->Embed->get( 'bar' ));
	}
}