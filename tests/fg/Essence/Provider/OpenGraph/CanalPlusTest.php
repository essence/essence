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
 *	Test case for CanalPlus.
 */

class CanalPlusTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $CanalPlus = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->CanalPlus = new CanalPlus( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue( $this->CanalPlus->canEmbed( 'http://www.canalplus.fr/c-divertissement/pid1787-c-groland.html' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse( $this->CanalPlus->canEmbed( 'http://www.unsupported.com' ));
	}
}
