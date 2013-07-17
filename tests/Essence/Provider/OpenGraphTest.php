<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;

use PHPUnit_Framework_TestCase;
use Essence\Dom\Parser\Native as NativeDomParser;
use Essence\Http\Client\Native as NativeHttpClient;



/**
 *	Test case for OpenGraph.
 */

class OpenGraphTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $OpenGraph = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->OpenGraph = new OpenGraph(
			new NativeHttpClient( ),
			new NativeDomParser( )
		);
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$this->assertNotNull(
			$this->OpenGraph->embed( 'file://' . ESSENCE_HTTP . 'valid.html' )
		);
	}



	/**
	 *	@todo fix it for travis
	 */
	/*
	public function testEmbedInvalid( ) {

		$this->setExpectedException( '\\Essence\\Exception' );

		$this->OpenGraph->embed( 'file://' . ESSENCE_HTTP . 'invalid.html' );
	}
	*/



	/**
	 *
	 */

	public function testHtmlPhoto( ) {

		$this->assertEquals(
			'<img src="http://static.foo.com/photos/123456.jpg" alt="Title" width="1024" height="768" />',
			OpenGraph::html(
				array(
					'og:type' => 'photo',
					'og:url' => 'http://static.foo.com/photos/123456.jpg',
					'og:title' => 'Title',
					'og:width' => 1024,
					'og:height' => 768
				)
			)
		);
	}



	/**
	 *
	 */

	public function testHtmlVideo( ) {

		$this->assertStringStartsWith(
			'<iframe src="http://www.youtube.com/v/123456" alt="Title" width="1024" height="768"',
			OpenGraph::html(
				array(
					'og:type' => 'video',
					'og:url' => 'http://www.youtube.com/watch?v=123456',
					'og:title' => 'Title',
					'og:video' => 'http://www.youtube.com/v/123456',
					'og:video:width' => 1024,
					'og:video:height' => 768
				)
			)
		);
	}



	/**
	 *
	 */

	public function testHtmlLink( ) {

		$this->assertEquals(
			'<a href="http://www.youtube.com/watch?v=123456" alt="A video">Title</a>',
			OpenGraph::html(
				array(
					'og:url' => 'http://www.youtube.com/watch?v=123456',
					'og:title' => 'Title',
					'og:description' => 'A video'
				)
			)
		);
	}
}
