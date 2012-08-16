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
 *	Test case for Essence.
 */

class EssenceTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Essence = null;



	/**
	 *
	 */

	public function setUp( ) {

		$Collection = $this->getMock( '\\Essence\\ProviderCollection' );
		$Collection->expects( $this->any( ))
			->method( 'doSomething' )
			->will( $this->returnValue( 'foo' ));

		TestableEssence::stub( $Collection );
	}



	/**
	 *
	 */

	public function testConfigure( ) {

		$Collection = $this->getMock( '\\Essence\\ProviderCollection', array( 'load' ));
		$Collection->expects( $this->any( ))
			->method( 'load' )
			->with( $this->equalTo( array( 'Provider' )));

		TestableEssence::stub( $Collection );
		TestableEssence::configure( array( 'Provider' ));
	}



	/**
	 *
	 */

	public function testExtract( ) {

		$Collection = $this->getMock( '\\Essence\\ProviderCollection', array( 'load' ));
		$Collection->expects( $this->any( ))
			->method( 'load' )
			->with( $this->equalTo( array( 'Provider' )));

		TestableEssence::stub( $Collection );
		TestableEssence::configure( array( 'Provider' ));
	}



	/**
	 *
	 */

	public function testFetch( ) {

		$Collection = $this->getMock(
			'\\Essence\\ProviderCollection',
			array( 'providerIndex', 'provider' )
		);

		$Collection->expects( $this->any( ))
			->method( 'providerIndex' )
			->will( $this->onConsecutiveCalls( 0, 2 ));

		$Collection->expects( $this->any( ))
			->method( 'provider' )
			->will( $this->returnValue( new TestableProvider ));

		TestableEssence::stub( $Collection );
		TestableEssence::fetch( 'http://www.foo.com/bar' );
	}



	/**
	 *
	 */

	public function testFetchAll( ) {

	}



	/**
	 *
	 */

	public function testErrors( ) {

	}



	/**
	 *
	 */

	public function testLastError( ) {
		
	}
}



/**
 *
 */

class TestableEssence extends Essence {

	/**
	 *
	 */

	protected function __construct( ) { }



	/**
	 *
	 */

	public static function stub( $ProviderCollection ) {

		$_this = self::_instance( );
		$_this->_ProviderCollection = $ProviderCollection;
	}
}



/**
 *
 */

class TestableProvider extends Provider {

	/**
	 *
	 */

	protected function _fetch( $url ) {

		return new Embed( array( ));
	}

}