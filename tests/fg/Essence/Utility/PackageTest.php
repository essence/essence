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
 *	Test case for Package.
 */

class PackageTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Package = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Package = new Package( ESSENCE_PACKAGE );
	}



	/**
	 *
	 */

	public function testPath( ) {

		$dir = dirname( __FILE__ );

		$Package = new Package( $dir );
		$this->assertEquals( $dir, $Package->path( ));
	}



	/**
	 *
	 */

	public function testFilePath( ) {

		$dir = dirname( __FILE__ );

		$Package = new Package( __FILE__ );
		$this->assertEquals( $dir, $Package->path( ));
	}



	/**
	 *
	 */

	public function testSeparator( ) {

		$Package = new Package( 'foo', '\\' );
		$this->assertEquals( '\\', $Package->separator( ));
	}



	/**
	 *
	 */

	public function testClassesRecursive( ) {

		$this->assertEquals(
			array(
				'Foo',
				'Generic',
				'Sushi\\Bar'
			),
			$this->Package->classes( array( ), true )
		);
	}



	/**
	 *
	 */

	public function testClassesSubPackage( ) {

		$this->assertEquals(
			array(
				'Sushi\\Bar'
			),
			$this->Package->classes( array( 'Sushi' ))
		);
	}
}
