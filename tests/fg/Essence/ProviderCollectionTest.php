<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( dirname( __FILE__ )))
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

		$this->Collection = new ProviderCollection( );

		$ClassLoader = new ClassLoader( ESSENCE_RESOURCES );
		$ClassLoader->register( );
	}



	/**
	 *
	 */

	public function testLoadAll( ) {

		$this->Collection->load( );

		$this->assertAttributeNotEmpty( '_providers', $this->Collection );
	}



	/**
	 *
	 */

	public function testLoad( ) {

		$this->Collection->load( array( 'Foo' ));

		$this->assertTrue( $this->Collection->hasProvider( 'foo' ));
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

		$this->assertTrue( $this->Collection->hasProvider( 'bar' ));
	}



	/**
	 *
	 */

	public function testProviders( ) {

		$this->Collection->load( array( 'Sushi/Bar' ));

		$this->assertTrue(
			in_array(
				new \fg\Essence\Provider\Sushi\Bar( ),
				$this->Collection->providers( 'bar' )
			)
		);
	}
}
