<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for bandcamp.
 */

class BandcampTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Bandcamp = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Bandcamp = new Bandcamp( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue( $this->Bandcamp->canEmbed( 'http://www.ted.com/talks/katherine_kuchenbecker_the_technology_of_touch.html' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse( $this->Bandcamp->canEmbed( 'http://www.unsupported.com' ));
	}
}
