<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use Essence\Provider;
use Essence\Provider\OEmbed;
use Essence\Di\Container;



/**
 *
 */

class CollectionProviderImplementation extends Provider {

	/**
	 *
	 */

	protected function _embed( $url, $options ) { }

}



/**
 *	Test case for ProviderCollection.
 */

class ProviderCollectionTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Provider = null;



	/**
	 *
	 */

	public $Collection = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Provider = new CollectionProviderImplementation( );

		$Container = new Container( );
		$Container->set( 'OEmbed', $this->Provider );

		$this->Collection = new ProviderCollection( $Container );
		$this->Collection->setProperties(
			array(
				'Foo' => array(
					'class' => 'OEmbed',
					'filter' => '#^foo$#'
				),
				'Bar' => array(
					'class' => 'OpenGraph',
					'filter' => function ( $url ) {
						return ( $url === 'bar' );
					}
				)
			)
		);
	}



	/**
	 *
	 */

	public function testHasProvider( ) {

		$this->assertTrue( $this->Collection->hasProvider( 'foo' ));
		$this->assertTrue( $this->Collection->hasProvider( 'bar' ));
		$this->assertFalse( $this->Collection->hasProvider( 'baz' ));
	}



	/**
	 *
	 */

	public function testProviders( ) {

		$providers = $this->Collection->providers( 'foo' );

		if ( empty( $providers )) {
			$this->fail( 'There should be one provider.' );
		} else {
			$this->assertEquals( $this->Provider, array_shift( $providers ));
		}
	}
}
