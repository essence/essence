<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OpenGraph;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once
		dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Generic.
 */

class GenericTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testFetch( ) {

		$Generic = new Generic( );

		$this->assertNotNull(
			$Generic->fetch( 'file://' . ESSENCE_TEST_HTTP . 'valid.html' )
		);
	}
}