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
 *	Test case for Hulu.
 */

class HuluTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Hulu = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Hulu = new Hulu( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue(
			$this->Hulu->canEmbed( 'http://www.hulu.com/watch/2183' )
		);
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse(
			$this->Hulu->canEmbed( 'http://www.hulu.com/movies/trailers' )
		);
	}
}
