<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Http\Client;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Native.
 */

class NativeTest extends TestCase {

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
