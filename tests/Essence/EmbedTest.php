<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
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

	public function setUp( ) {

		$Embed = new Embed( array( ));
		$this->baseProperties = $Embed->get( );
	}



	/**
	 *
	 */

	public function testConstruct( ) {

		$Embed = new Embed( $this->customProperties );
		$this->assertEquals( $this->customProperties['title'], $Embed->get( 'title' ));

		$Embed = new Embed( $this->customProperties, array( 'title' => 'title2' ));
		$this->assertEquals( $this->customProperties['title'], $Embed->get( 'title2' ));
	}



	/**
	 *
	 */

	public function testHas( ) {

		$Embed = new Embed( $this->customProperties );
		$this->assertTrue( $Embed->has( 'title' ));
		$this->assertFalse( $Embed->has( 'foo' ));
	}



	/**
	 *
	 */

	public function testGet( ) {

		$Embed = new Embed( $this->customProperties );
		$this->assertEquals( $this->customProperties['title'], $Embed->get( 'title' ));
		$this->assertFalse( $Embed->get( 'foo' ));
	}



	/**
	 *
	 */

	public function testSet( ) {

		$Embed = new Embed( array( ));

		$Embed->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $Embed->get( 'foo' ));
		
		$Embed->set( array( 'bar' => 'foo' ));
		$this->assertEquals( 'foo', $Embed->get( 'bar' ));
	}
}