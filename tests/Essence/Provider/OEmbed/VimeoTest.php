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
 *	Test case for Vimeo.
 */

class VimeoTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testPrepare( ) {

		$Vimeo = new Vimeo( );
		$Reflection = new \ReflectionClass( '\\Essence\\Provider\\OEmbed\\Youtube' );

		$property = $Reflection->getProperty( '_endpoint' );
		$property->setAccessible( true );
		$property->setValue( $Vimeo, 'file://' . ESSENCE_TEST_HTTP . '%s.json' );
	
		$Embed = $Vimeo->fetch( 'http://player.vimeo.com/video/20830433' );

		$this->assertEquals( 'http://www.vimeo.com/20830433', $Embed->get( 'url' ));
	}
}
