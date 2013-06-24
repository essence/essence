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

	public $ClassLoader = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->ClassLoader = new ClassLoader( ESSENCE_RESOURCES );
		$this->ClassLoader->register( );
	}



	/**
	 *
	 */

	public function testRegister( ) {

		$this->assertTrue(
			in_array(
				array( $this->ClassLoader, 'load' ),
				spl_autoload_functions( )
			)
		);
	}



	/**
	 *
	 */

	public function testLoad( ) {

		$this->assertTrue( class_exists( '\\fg\\Essence\\Provider\\Foo' ));
		$this->assertTrue( class_exists( '\\fg\\Essence\\Provider\\Sushi\\Bar' ));
	}



	/**
	 *
	 */

	public function testLoadUndefined( ) {

		$this->assertFalse( class_exists( '\\fg\\Essence\\Provider\\Undefined' ));
	}
}
