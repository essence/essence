<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



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

		$this->Collection = new ProviderCollection(
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
			$this->assertEquals(
				'Essence\Provider\OEmbed',
				get_class( array_shift( $providers ))
			);
		}
	}
}
