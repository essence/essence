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
 *	Test case for Twitter.
 */

class TwitterTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Twitter = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Twitter = new Twitter( );
		$Reflection = new \ReflectionClass( '\\fg\\Essence\\Provider\\OEmbed\\Twitter' );

		$Property = $Reflection->getProperty( '_endpoint' );
		$Property->setAccessible( true );
		$Property->setValue( $this->Twitter, 'file://' . ESSENCE_HTTP . '%s.json' );
	}



	/**
	 *
	 */

	public function testPrepare( ) {

		$Media = $this->Twitter->embed( 'https://twitter.com/Ouiche_Lorraine/status/278534502748405760' );

		$this->assertEquals( 'La_classe_americaine', $Media->authorName );
	}
}
