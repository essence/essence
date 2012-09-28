<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once
		dirname( dirname( dirname( __FILE__ )))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Essence.
 */

class EssenceTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testConfigure( ) {

		$Collection = $this->getMock( '\\fg\\Essence\\ProviderCollection', array( 'load' ));
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

		$Collection = $this->getMock( '\\fg\\Essence\\ProviderCollection', array( 'hasProvider' ));
		$Collection->expects( $this->any( ))
			->method( 'hasProvider' )
			->will( $this->onConsecutiveCalls( false, true, false, true, true, true ));

		TestableEssence::stub( $Collection );

		$this->assertEquals(
			array(
				'http://www.foo.com',
				'http://www.embed.com',
				'http://www.iframe.com'
			),
			TestableEssence::extract( 'file://' . ESSENCE_HTTP . 'valid.html' )
		);
	}



	/**
	 *
	 */

	public function testExtractEmbeddable( ) {

		$Collection = $this->getMock( '\\fg\\Essence\\ProviderCollection', array( 'hasProvider' ));
		$Collection->expects( $this->any( ))
			->method( 'hasProvider' )
			->will( $this->returnValue( true ));

		TestableEssence::stub( $Collection );

		$url = 'file://' . ESSENCE_HTTP . 'valid.html';
		$this->assertEquals( array( $url ), TestableEssence::extract( $url ));
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$Collection = $this->getMock(
			'\\fg\\Essence\\ProviderCollection',
			array( 'providerIndex', 'provider' )
		);

		$Collection->expects( $this->any( ))
			->method( 'providerIndex' )
			->will( $this->onConsecutiveCalls( 0, 2 ));

		$Collection->expects( $this->any( ))
			->method( 'provider' )
			->will( $this->returnValue( new TestableProvider( )));

		TestableEssence::stub( $Collection );
		$this->assertNotNull( TestableEssence::embed( 'http://www.foo.com/bar' ));
	}



	/**
	 *
	 */

	public function testEmbedAll( ) {

		$Collection = $this->getMock(
			'\\fg\\Essence\\ProviderCollection',
			array( 'providerIndex', 'provider' )
		);

		$Collection->expects( $this->any( ))
			->method( 'providerIndex' )
			->will( $this->returnValue( 0 ));

		$Collection->expects( $this->any( ))
			->method( 'provider' )
			->will( $this->returnValue( new TestableProvider( )));

		TestableEssence::stub( $Collection );
		$this->assertEquals(
			array(
				'one' => new Media( array( 'url' => 'one' )),
				'two' => new Media( array( 'url' => 'two' ))
			),
			TestableEssence::embedAll( array( 'one', 'two' ))
		);
	}



	/**
	 *
	 */

	public function testErrors( ) {

		TestableEssence::log( new Exception( 'one' ));
		TestableEssence::log( new Exception( 'two' ));

		$this->assertEquals(
			array(
				new Exception( 'one' ),
				new Exception( 'two' )
			),
			TestableEssence::errors( )
		);
	}



	/**
	 *
	 */

	public function testLastError( ) {

		TestableEssence::log( new Exception( 'one' ));
		TestableEssence::log( new Exception( 'two' ));

		$this->assertEquals( new Exception( 'two' ), TestableEssence::lastError( ));
	}



	/**
	 *
	 */

	public function testLastErrorEmpty( ) {

		$this->assertFalse( TestableEssence::lastError( ));
	}



	/**
	 *
	 */

	public function tearDown( ) {

		TestableEssence::kill( );
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

	public static function kill( ) {

		self::$_Instance = null;
	}



	/**
	 *
	 */

	public static function stub( $ProviderCollection ) {

		$_this = self::_instance( );
		$_this->_ProviderCollection = $ProviderCollection;
	}



	/**
	 *
	 */

	public static function log( Exception $exception ) {
		$_this = self::_instance( );
		$_this->_log( $exception );
	}
}



/**
 *
 */

class TestableProvider extends Provider {

	/**
	 *
	 */

	protected function _embed( $url ) {

		return new Media( array( ));
	}
}
