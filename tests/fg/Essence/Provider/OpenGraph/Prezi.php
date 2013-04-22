<?php

/**
 *	@author Laughingwithu <laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Prezi.
 */

class PreziTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Prezi = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Prezi = new Prezi( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue( $this->Prezi->canEmbed( 'http://prezi.com/bq2blf6kdefn/6-cs-of-successful-social-marketing/' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse( $this->Prezi->canEmbed( 'http://www.unsupported.com' ));
	}
}
