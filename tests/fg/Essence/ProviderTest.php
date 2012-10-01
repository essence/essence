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
 *
 */

class ConcreteProvider extends Provider {

	/**
	 *
	 */

	protected function _embed( $url ) {

		return new Media( array( 'title' => 'Title' ));
	}
}



/**
 *	Test case for Provider.
 */

class ProviderTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testCanEmbed( ) {

		$Provider = new ConcreteProvider( '#[a-z]+#' );

		$this->assertTrue( $Provider->canEmbed( 'abc' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$Provider = new ConcreteProvider( '#[a-z]+#' );

		$this->assertFalse( $Provider->canEmbed( '123' ));
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$Provider = new ConcreteProvider( Provider::anything );

		$this->assertEquals(
			new Media(
				array(
					'title' => 'Title',
					'url' => 'http://foo.bar'
				)
			),
			$Provider->embed( '  http://foo.bar  ' )
		);
	}
}
