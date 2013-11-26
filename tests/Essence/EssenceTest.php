<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use PHPUnit_Framework_TestCase;
use Essence\Di\Container as Container;
use Essence\Cache\Engine\Null as NullCacheEngine;
use Essence\Dom\Parser\Native as NativeDomParser;
use Essence\Http\Client\Native as NativeHttpClient;
use Essence\Log\Logger\Null as NullLogger;



/**
 *	Test case for Essence.
 */

class EssenceTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Essence = null;



	/**
	 *
	 */

	public function setUp( ) {

		$Media = new Media(
			array(
				'title' => 'Title',
				'html' => 'HTML'
			)
		);

		$Provider = $this->getMockForAbstractClass(
			'\\Essence\\Provider',
			array( new NullLogger( ))
		);

		$Provider
			->expects( $this->any( ))
			->method( '_embed' )
			->will( $this->returnValue( $Media ));

		$Collection = $this->getMock(
			'\\Essence\\Provider\\Collection',
			array( ),
			array( new Container( ))
		);

		$Collection
			->expects( $this->any( ))
			->method( 'hasProvider' )
			->will( $this->onConsecutiveCalls( true, false, true, true ));

		$Collection
			->expects( $this->any( ))
			->method( 'providers' )
			->will( $this->returnValue( array( $Provider )));

		$this->Essence = new Essence(
			$Collection,
			new NullCacheEngine( ),
			new NativeHttpClient( ),
			new NativeDomParser( ),
			new NullLogger( )
		);
	}



	/**
	 *
	 */

	public function testExtract( ) {

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

		$this->assertNotNull( $this->Essence->embed( 'http://www.foo.com/bar' ));
	}



	/**
	 *
	 */

	public function testEmbedAll( ) {

		$urls = array( 'one', 'two' );
		$medias = $this->Essence->embedAll( array( 'one', 'two' ));

		$this->assertEquals( $urls, array_keys( $medias ));

	}



	/**
	 *
	 */

	public function testReplace( ) {

		$this->assertEquals(
			'foo HTML bar',
			$this->Essence->replace( 'foo http://www.example.com bar' )
		);
	}



	/**
	 *
	 */

	public function testReplaceSingleUrl( ) {

		$this->assertEquals(
			'HTML',
			$this->Essence->replace( 'http://www.example.com' )
		);
	}



	/**
	 *
	 */

	public function testReplaceTagSurroundedUrl( ) {

		$this->assertEquals(
			'<span>HTML</span>',
			$this->Essence->replace( '<span>http://www.example.com</span>' )
		);
	}



	/**
	 *
	 */

	public function testReplaceWithTemplate( ) {

		$this->assertEquals(
			'foo <h1>Title</h1> bar',
			$this->Essence->replace( 'foo http://www.example.com bar', function( $Media ) {
				return '<h1>' . $Media->title . '</h1>';
			})
		);
	}



	/**
	 *
	 */

	public function testDontReplaceLinks( ) {

		$link = '<a href="http://example.com">baz</a>';
		$this->assertEquals( $link, $this->Essence->replace( $link ));

		$link = '<a href=\'http://example.com\'>baz</a>';
		$this->assertEquals( $link, $this->Essence->replace( $link ));
	}



	/**
	 *
	 */

	public function testReplaceQuotesSurroundedUrl( ) {

		$this->assertEquals( '"HTML"', $this->Essence->replace( '"http://example.com"' ));
	}
}
