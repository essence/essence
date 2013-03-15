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
 *	Test case for SoundCloud.
 */

class SoundCloudTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $SoundCloud = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->SoundCloud = new SoundCloud( );
		$Reflection = new \ReflectionClass( '\\fg\\Essence\\Provider\\OEmbed\\SoundCloud' );

		$Property = $Reflection->getProperty( '_endpoint' );
		$Property->setAccessible( true );
		$Property->setValue( $this->SoundCloud, 'file://' . ESSENCE_HTTP . '%s.json' );
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$Media = $this->SoundCloud->embed( 'https://soundcloud.com/math-electro-des-champs-1/math-electro-des-champs-super' );

		$this->assertEquals( 'Math electro des champs', $Media->authorName );
	}
}
