<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Http\Client;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Native.
 */

class NativeTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Native = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Native = new Native( );
	}



	/**
	 *
	 */

	public function testGet( ) {

		$this->assertNotEmpty( $this->Native->get( 'file://' . __FILE__ ));
	}



	/**
	 *
	 */

	public function testGetUnreachable( ) {

		$this->setExpectedException( '\\Essence\\Http\\Exception' );
		$this->Native->get( 'file://' . __FILE__ . '.unreachable' );
	}
}
