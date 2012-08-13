<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( __FILE__ )) . DIRECTORY_SEPARATOR . 'bootstrap.php';
}

use org\bovigo\vfs\vfsStream;



/**
 *	Test case for ClassLoader.
 */

class ClassLoaderTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $vfs = null;



	/**
	 *
	 */

	public $ClassLoader = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->vfs = vfsStream::setup(
			'root',
			null,
			array(
				'Package' => array(
					'Foo.php' => 'class Foo { }',
					'Sushi' => array(
						'Bar.php' => 'class Bar { }'
					)
				)
			)
		);

		$this->ClassLoader = new ClassLoader( vfsStream::url( 'root' ));
	}



	/**
	 *
	 */

	public function testRegister( ) {

		$this->ClassLoader->register( );

		$this->assertTrue(
			in_array(
				array( $this->ClassLoader, 'load' ),
				spl_autoload_functions( )
			)
		);
	}



	/**
	 *	@see https://github.com/mikey179/vfsStream/issues/22
	 */

	public function testLoad( ) {

		$this->assertTrue( class_exists( '\Package\Foo', true ));
		$this->assertTrue( class_exists( '\Package\Sushi\Bar', true ));
	}
}