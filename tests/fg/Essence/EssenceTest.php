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

class TestableEssence extends Essence {

	/**
	 *
	 */

	public function setCollection( ProviderCollection $Collection ) {

		$this->_Collection = $Collection;
	}



	/**
	 *
	 */

	public function log( Exception $Exception ) {

		$this->_log( $Exception );
	}
}



/**
 *
 */

class TestableProvider extends Provider {

	/**
	 *
	 */

	public $mediaProperties = array( );



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) {

		return new Media( $this->mediaProperties );
	}
}



/**
 *	Test case for Essence.
 */

class EssenceTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Collection = null;



	/**
	 *
	 */

	public $Essence = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Collection = $this->getMock( '\\fg\\Essence\\ProviderCollection' );

		$this->Essence = new TestableEssence( );
		$this->Essence->setCollection( $this->Collection );
	}



	/**
	 *
	 */

	public function testExtract( ) {

		$this->Collection->expects( $this->any( ))
			->method( 'hasProvider' )
			->will( $this->onConsecutiveCalls( false, true, false, true, true ));

		$this->assertEquals(
			array(
				'http://www.foo.com',
				'http://www.embed.com',
				'http://www.iframe.com'
			),
			$this->Essence->extract( 'file://' . ESSENCE_HTTP . 'valid.html' )
		);
	}



	/**
	 *
	 */

	public function testExtractEmbeddable( ) {

		$this->Collection->expects( $this->any( ))
			->method( 'hasProvider' )
			->will( $this->returnValue( true ));

		$url = 'file://' . ESSENCE_HTTP . 'valid.html';
		$this->assertEquals( array( $url ), $this->Essence->extract( $url ));
	}



	/**
	 *
	 */

	public function testExtractHtml( ) {

		$this->Collection->expects( $this->any( ))
			->method( 'hasProvider' )
			->will( $this->onConsecutiveCalls( true, false, true, true ));

		$html = <<<HTML
			<a href="http://www.foo.com">Foo</a>
			<a href="http://www.bar.com">Bar</a>
			<embed src="http://www.embed.com"></embed>
			<iframe src="http://www.iframe.com"></iframe>
HTML;

		$this->assertEquals(
			array(
				'http://www.foo.com',
				'http://www.embed.com',
				'http://www.iframe.com'
			),
			$this->Essence->extract( $html )
		);
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( new TestableProvider( ))));

		$this->assertNotNull( $this->Essence->embed( 'http://www.foo.com/bar' ));
	}



	/**
	 *
	 */

	public function testEmbedAll( ) {

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( new TestableProvider( ))));

		$this->assertEquals(
			array(
				'one' => new Media( array( 'url' => 'one' )),
				'two' => new Media( array( 'url' => 'two' ))
			),
			$this->Essence->embedAll( array( 'one', 'two' ))
		);
	}



	/**
	 *
	 */

	public function testReplace( ) {

		$Provider = new TestableProvider( );
		$Provider->mediaProperties = array( 'html' => '<div></div>' );

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->assertEquals(
			'foo <div></div> bar',
			$this->Essence->replace( 'foo http://www.example.com bar' )
		);
	}



	/**
	 *
	 */

	public function testReplaceWithTemplate( ) {

		$Provider = new TestableProvider( );
		$Provider->mediaProperties = array( 'title' => 'Example' );

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->assertEquals(
			'foo <h1>Example</h1> bar',
			$this->Essence->replace( 'foo http://www.example.com bar', '<h1>%title%</h1>' )
		);
	}



	/**
	 *
	 */

	public function testDontReplaceLinks( ) {

		$Provider = new TestableProvider( );
		$Provider->mediaProperties = array( 'html' => '<div></div>' );

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->assertEquals(
			'foo <a href="http://example.com">baz</a> bar',
			$this->Essence->replace( 'foo <a href="http://example.com">baz</a> bar' )
		);
	}



	/**
	 *
	 */

	public function testErrors( ) {

		$this->Essence->log( new Exception( 'one' ));
		$this->Essence->log( new Exception( 'two' ));

		$this->assertEquals(
			array(
				new Exception( 'one' ),
				new Exception( 'two' )
			),
			$this->Essence->errors( )
		);
	}



	/**
	 *
	 */

	public function testLastError( ) {

		$this->Essence->log( new Exception( 'one' ));
		$this->Essence->log( new Exception( 'two' ));

		$this->assertEquals( new Exception( 'two' ), $this->Essence->lastError( ));
	}



	/**
	 *
	 */

	public function testLastErrorEmpty( ) {

		$this->assertNull( $this->Essence->lastError( ));
	}
}
