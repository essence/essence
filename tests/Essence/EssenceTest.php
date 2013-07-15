<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



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

		$this->Collection = $this->getMock( '\\Essence\\ProviderCollection' );
		$this->Client = $this->getMock( '\\Essence\\Http\\Client' );
		$this->Essence = new Essence( $this->Collection );
	}



	/**
	 *
	 */

	public function testExtract( ) {

		$this->Collection->expects( $this->any( ))
			->method( 'hasProvider' )
			->will( $this->onConsecutiveCalls( true, false, true, true ));

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
		$Provider->mediaProperties = array( 'html' => 'HTML' );

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->assertEquals(
			'foo HTML bar',
			$this->Essence->replace( 'foo http://www.example.com bar' )
		);
	}



	/**
	 *
	 */

	public function testReplaceSingleUrl( ) {

		$Provider = new TestableProvider( );
		$Provider->mediaProperties = array( 'html' => 'HTML' );

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->assertEquals(
			'HTML',
			$this->Essence->replace( 'http://www.example.com' )
		);
	}



	/**
	 *
	 */

	public function testReplaceTagSurroundedUrl( ) {

		$Provider = new TestableProvider( );
		$Provider->mediaProperties = array( 'html' => 'HTML' );

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->assertEquals(
			'<span>HTML</span>',
			$this->Essence->replace( '<span>http://www.example.com</span>' )
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
			$this->Essence->replace( 'foo http://www.example.com bar', function( $Media ) {
				return '<h1>' . $Media->title . '</h1>';
			})
		);
	}



	/**
	 *
	 */

	public function testDontReplaceLinks( ) {

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( new TestableProvider( ))));

		$link = '<a href="http://example.com">baz</a>';
		$this->assertEquals( $link, $this->Essence->replace( $link ));

		$link = '<a href=\'http://example.com\'>baz</a>';
		$this->assertEquals( $link, $this->Essence->replace( $link ));
	}



	/**
	 *
	 */

	public function testReplaceQuotesSurroundedUrl( ) {

		$Provider = new TestableProvider( );
		$Provider->mediaProperties = array( 'html' => 'HTML' );

		$this->Collection->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->assertEquals( '"HTML"', $this->Essence->replace( '"http://example.com"' ));
	}
}
