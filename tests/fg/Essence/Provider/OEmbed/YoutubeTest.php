<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
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

		$Reflection = new \ReflectionClass( '\\fg\\Essence\\Provider\\OEmbed\\Youtube' );

		$Property = $Reflection->getProperty( '_endpoint' );
		$Property->setAccessible( true );
		$Property->setValue( $this->Youtube, 'file://' . ESSENCE_HTTP . '%s.json' );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue( $this->Youtube->canEmbed( 'http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise' ));
	}



	/**
	 *
	 */

	public function testCanEmbedOldSchool( ) {

		$this->assertTrue( $this->Youtube->canEmbed( 'http://www.youtube.com/v/oHg5SJYRHA0' ));
	}



	/**
	 *
	 */

	public function testCanEmbedEmbed( ) {

		$this->assertTrue( $this->Youtube->canEmbed( 'http://www.youtube.com/embed/oHg5SJYRHA0' ));
	}



	/**
	 *
	 */

	public function testCanEmbedShortened( ) {

		$this->assertTrue( $this->Youtube->canEmbed( 'http://youtu.be/oHg5SJYRHA0' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse( $this->Youtube->canEmbed( 'http://www.unsupported.com' ));
	}



	/**
	 *
	 */

	public function testPrepare( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareOldSchool( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/v/oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareEmbed( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/embed/oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareShortened( ) {

		$Media = $this->Youtube->embed( 'http://youtu.be/oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareAlreadyPrepared( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/watch?v=oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}
}
