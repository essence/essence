<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Utility;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for ClassLoader.
 */

class ClassLoaderTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testSetup( ) {

		ClassLoader::setup( ESSENCE_RESOURCES );

		$this->assertTrue( class_exists( '\\fg\\Essence\\Provider\\Foo' ));
		$this->assertTrue( class_exists( '\\fg\\Essence\\Provider\\Sushi\\Bar' ));
	}
}
