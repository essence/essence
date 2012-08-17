<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OEmbed;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once
		dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Youtube.
 */

class YoutubeTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Youtube = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Youtube = new Youtube( );

		$Reflection = new \ReflectionClass( '\\Essence\\Provider\\OEmbed\\Youtube' );

		$Property = $Reflection->getProperty( '_endpoint' );
		$Property->setAccessible( true );
		$Property->setValue( $this->Youtube, 'file://' . ESSENCE_TEST_HTTP . '%s.json' );
	}



	/**
	 *
	 */

	public function testCanFetch( ) {

		$this->assertTrue( $this->Youtube->canFetch( 'http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise' ));
	}



	/**
	 *
	 */

	public function testCanFetchOldSchool( ) {

		$this->assertTrue( $this->Youtube->canFetch( 'http://www.youtube.com/v/oHg5SJYRHA0' ));
	}



	/**
	 *
	 */

	public function testCanFetchEmbed( ) {

		$this->assertTrue( $this->Youtube->canFetch( 'http://www.youtube.com/embed/oHg5SJYRHA0' ));
	}



	/**
	 *
	 */

	public function testCanFetchShortened( ) {

		$this->assertTrue( $this->Youtube->canFetch( 'http://youtu.be/oHg5SJYRHA0' ));
	}



	/**
	 *
	 */

	public function testCantFetch( ) {

		$this->assertFalse( $this->Youtube->canFetch( 'http://www.unsupported.com' ));
	}



	/**
	 *
	 */

	public function testPrepare( ) {

		$Embed = $this->Youtube->fetch( 'http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise' );
		
		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Embed->url
		);
	}



	/**
	 *
	 */

	public function testPrepareOldSchool( ) {

		$Embed = $this->Youtube->fetch( 'http://www.youtube.com/v/oHg5SJYRHA0' );
		
		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Embed->url
		);
	}



	/**
	 *
	 */

	public function testPrepareEmbed( ) {

		$Embed = $this->Youtube->fetch( 'http://www.youtube.com/embed/oHg5SJYRHA0' );
		
		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Embed->url
		);
	}



	/**
	 *
	 */

	public function testPrepareShortened( ) {

		$Embed = $this->Youtube->fetch( 'http://youtu.be/oHg5SJYRHA0' );
		
		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Embed->url
		);
	}



	/**
	 *
	 */

	public function testPrepareAlreadyPrepared( ) {

		$Embed = $this->Youtube->fetch( 'http://www.youtube.com/watch?v=oHg5SJYRHA0' );
		
		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Embed->url
		);
	}
}
