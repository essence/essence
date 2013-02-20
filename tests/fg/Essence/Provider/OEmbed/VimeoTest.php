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
 *	Test case for Vimeo.
 */

class VimeoTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Vimeo = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Vimeo = new Vimeo( );
		$Reflection = new \ReflectionClass( '\\fg\\Essence\\Provider\\OEmbed\\Vimeo' );

		$Property = $Reflection->getProperty( '_endpoint' );
		$Property->setAccessible( true );
		$Property->setValue( $this->Vimeo, 'file://' . ESSENCE_HTTP . '%s.json' );
	}



	/**
	 *
	 */

	public function testPrepare( ) {

		$Media = $this->Vimeo->embed( 'http://player.vimeo.com/video/20830433' );

		$this->assertEquals( 'http://www.vimeo.com/20830433', $Media->url );
	}



	/**
	 *
	 */

	public function testPrepareAlreadyPrepared( ) {

		$Media = $this->Vimeo->embed( 'http://www.vimeo.com/20830433' );

		$this->assertEquals( 'http://www.vimeo.com/20830433', $Media->url );
	}
}
