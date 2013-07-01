<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

use fg\Essence\Utility\Package;
use fg\Essence\Provider\Sushi\Bar;

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

		$this->Collection = new ProviderCollection(
			array(
				'Foo' => array(
					'class' => 'Foo',
					'pattern' => '#^foo$#'
				),
				'Bar' => array(
					'class' => 'Sushi/Bar',
					'pattern' => '#^bar$#'
				)
			)
		);
	}



	/**
	 *
	 */

	public function testHasProvider( ) {

		$this->assertTrue( $this->Collection->hasProvider( 'foo' ));
	}



	/**
	 *
	 */

	public function testProviders( ) {

		$providers = $this->Collection->providers( 'bar' );

		if ( empty( $providers )) {
			$this->fail( 'There should be one provider.' );
		} else {
			$this->assertEquals(
				'fg\Essence\Provider\Sushi\Bar',
				get_class( $providers[ 0 ])
			);
		}
	}
}
