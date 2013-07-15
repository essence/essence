<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( __FILE__ ))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *
 */

class ConfigurableImplementation {

	use Configurable;



	/**
	 *
	 */

	protected $_properties = array(
		'one' => 1,
		'two' => 2
	);
}



/**
 *	Test case for Configurable.
 */

class ConfigurableTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Configurable = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Configurable = new ConfigurableImplementation( );
	}



	/**
	 *
	 */

	public function testMagicIsSet( ) {

		$this->assertTrue( isset( $this->Configurable->one ));
		$this->assertFalse( isset( $this->Configurable->unset ));
	}



	/**
	 *
	 */

	public function testMagicGet( ) {

		$this->assertEquals( 1, $this->Configurable->one );
	}



	/**
	 *
	 */

	public function testMagicSet( ) {

		$this->Configurable->foo = 'bar';
		$this->assertEquals( 'bar', $this->Configurable->foo );
	}



	/**
	 *
	 */

	public function testHas( ) {

		$this->assertTrue( $this->Configurable->has( 'one' ));
		$this->assertFalse( $this->Configurable->has( 'unset' ));
	}



	/**
	 *
	 */

	public function testGet( ) {

		$this->assertEquals( 1, $this->Configurable->get( 'one' ));
		$this->assertEmpty( $this->Configurable->get( 'unset' ));
	}



	/**
	 *
	 */

	public function testSet( ) {

		$this->Configurable->set( 'foo', 'bar' );
		$this->assertEquals( 'bar', $this->Configurable->foo );
	}
}
