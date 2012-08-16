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
 *	Test case for Generic.
 */

class GenericTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Generic = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Generic = new Generic( );

		$Reflection = new \ReflectionClass( '\\Essence\\Provider\\OEmbed\\Generic' );

		$Property = $Reflection->getProperty( '_endpoint' );
		$Property->setAccessible( true );
		$Property->setValue( $this->Generic, 'file://' . ESSENCE_TEST_HTTP . '%s.json' );
	}



	/**
	 *
	 */

	public function testFetch( ) {


	}
}
