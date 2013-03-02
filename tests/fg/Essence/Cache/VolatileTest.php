<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Cache;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Volatile.
 */

class VolatileTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Volatile = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Volatile = new Volatile( );
	}



	/**
	 *
	 */

	public function testHas( ) {

		$this->assertFalse( $this->Volatile->has( 'key' ));

		$this->Volatile->set( 'key', 'value' );
		$this->assertTrue( $this->Volatile->has( 'key' ));
	}



	/**
	 *
	 */

	public function testGetSet( ) {

		$this->assertNull( $this->Volatile->get( 'key' ));
		$this->assertEquals( $this->Volatile->get( 'key', 'value' ), 'value' );

		$this->Volatile->set( 'key', 'value' );
		$this->assertEquals( $this->Volatile->get( 'key' ), 'value' );
	}



	/**
	 *
	 */

	public function testClear( ) {

		$this->Volatile->set( 'key', 'value' );
		$this->Volatile->clear( );

		$this->assertNull( $this->Volatile->get( 'key' ));
	}
}
