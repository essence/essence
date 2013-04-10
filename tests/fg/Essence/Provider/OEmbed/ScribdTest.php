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
 *	Test case for Scribd.
 */

class ScribdTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Scribd = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Scribd = new Scribd( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue(
			$this->Scribd->canEmbed( 'http://fr.scribd.com/doc/50311486/coder-proprement' )
		);
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse(
			$this->Scribd->canEmbed( 'http://fr.scribd.com/r/art-design' )
		);
	}
}
