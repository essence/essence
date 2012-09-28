<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once
		dirname( dirname( dirname( __FILE__ )))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for ProviderCollection.
 */

class ProviderCollectionTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Collection = null;



	/**
	 *
	 */

	public function setUp( ) {

		$ClassLoader = new ClassLoader( ESSENCE_TEST . 'Resource' );
		$ClassLoader->register( );

		$this->Collection = new ProviderCollection( );
	}



	/**
	 *
	 */

	public function testLoad( ) {

		$this->Collection->load( array( 'Foo', 'Sushi/Bar' ));
	}



	/**
	 *
	 */

	public function testLoadUndefined( ) {

		$this->setExpectedException( '\\fg\\Essence\\Exception' );

		$this->Collection->load( array( 'Undefined' ));
	}



	/**
	 *
	 */

	public function testHasProvider( ) {

		$this->Collection->load( array( 'Foo' ));

		$this->assertTrue( $this->Collection->hasProvider( 'foo' ));
	}



	/**
	 *
	 */

	public function testProviderIndex( ) {

		$this->Collection->load( array( 'Foo', 'Sushi/Bar' ));

		$this->assertEquals( 1, $this->Collection->providerIndex( 'bar' ));
	}



	/**
	 *
	 */

	public function testProviderIndexNotFound( ) {

		$this->assertFalse( $this->Collection->providerIndex( '' ));
	}



	/**
	 *
	 */

	public function testProvider( ) {

		$this->Collection->load( array( 'Foo', 'Sushi/Bar' ));

		$this->assertEquals(
			new \fg\Essence\Provider\Sushi\Bar( ),
			$this->Collection->provider( 1 )
		);
	}



	/**
	 *
	 */

	public function testProviderNotFound( ) {

		$this->assertNull( $this->Collection->provider( -1 ));
	}
}
