<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( __FILE__ )) . DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *
 */

class ConcreteProvider extends Provider {

	/**
	 *
	 */

	protected function _fetch( $url ) {

		return new Embed( array( 'title' => 'Title' ));
	}	
}



/**
 *	Test case for Provider.
 */

class ProviderTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testCanFetch( ) {

		$Provider = new ConcreteProvider( '#[a-z]+#' );

		$this->assertTrue( $Provider->canFetch( 'abc' ));	
	}



	/**
	 *
	 */

	public function testCantFetch( ) {

		$Provider = new ConcreteProvider( '#[a-z]+#' );

		$this->assertFalse( $Provider->canFetch( '123' ));		
	}



	/**
	 *
	 */

	public function testFetch( ) {

		$Provider = new ConcreteProvider( Provider::anything );

		$this->assertEquals(
			new Embed(
				array(
					'title' => 'Title',
					'url' => 'http://foo.bar'
				)
			),
			$Provider->fetch( '  http://foo.bar  ' )
		);
	}
}