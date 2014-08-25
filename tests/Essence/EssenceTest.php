<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Dom\Parser\Native as NativeDomParser;
use Essence\Http\Client\Native as NativeHttpClient;



/**
 *	Test case for Essence.
 */
class EssenceTest extends TestCase {

	/**
	 *
	 */
	public $Essence = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Essence = new Essence([
			'Http' => new NativeHttpClient(),
			'Dom' => new NativeDomParser(),
			'Media' => new Media([
				'title' => 'Title',
				'html' => 'HTML'
			]),
			'Provider' => function($C) {
				$Provider = $this->getMockForAbstractClass(
					'\\Essence\\Provider'
				);

				$Provider
					->expects($this->any())
					->method('_embed')
					->will($this->returnValue($C->get('Media')));

				return $Provider;
			},
			'Collection.providers' => [
				'provider' => [
					'class' => 'Provider',
					'filter' => '#pass#i'
				]
			]
		]);
	}



	/**
	 *
	 */
	public function testExtract() {
		$this->assertEquals([
			'http://pass.foo.com',
			'http://pass.embed.com',
			'http://pass.iframe.com'
		], $this->Essence->extract('file://' . ESSENCE_HTTP . 'valid.html'));
	}



	/**
	 *
	 */
	public function testExtractHtml() {
		$html = <<<HTML
			<a href="http://pass.foo.com">Foo</a>
			<a href="http://fail.bar.com">Bar</a>
			<embed src="http://pass.embed.com"></embed>
			<iframe src="http://pass.iframe.com"></iframe>
HTML;

		$this->assertEquals([
			'http://pass.foo.com',
			'http://pass.embed.com',
			'http://pass.iframe.com'
		], $this->Essence->extract($html));
	}



	/**
	 *
	 */
	public function testEmbed() {
		$this->assertNotNull($this->Essence->embed('http://pass.foo.com/bar'));
	}



	/**
	 *
	 */
	public function testEmbedAll() {
		$urls = ['one', 'two'];
		$medias = $this->Essence->embedAll($urls);

		$this->assertEquals($urls, array_keys($medias));
	}



	/**
	 *
	 */
	public function testReplace() {
		$this->assertEquals(
			'foo HTML bar',
			$this->Essence->replace('foo http://pass.example.com bar')
		);
	}



	/**
	 *
	 */
	public function testReplaceSingleUrl() {
		$this->assertEquals(
			'HTML',
			$this->Essence->replace('http://pass.example.com')
		);
	}



	/**
	 *
	 */
	public function testReplaceTagSurroundedUrl() {
		$this->assertEquals(
			'<span>HTML</span>',
			$this->Essence->replace('<span>http://pass.example.com</span>')
		);
	}



	/**
	 *
	 */
	public function testReplaceWithTemplate() {
		$this->assertEquals(
			'foo <h1>Title</h1> bar',
			$this->Essence->replace('foo http://pass.example.com bar', function($Media) {
				return '<h1>' . $Media->title . '</h1>';
			})
		);
	}



	/**
	 *
	 */
	public function testDontReplaceLinks() {
		$link = '<a href="http://pass.com">baz</a>';
		$this->assertEquals($link, $this->Essence->replace($link));

		$link = '<a href="http://www.pass.com/watch?v=emgJtr9tIME">baz</a>';
		$this->assertEquals($link, $this->Essence->replace($link));

		$link = "<a href='http://pass.com'>baz</a>";
		$this->assertEquals($link, $this->Essence->replace($link));
	}



	/**
	 *
	 */
	public function testReplaceQuotesSurroundedUrl() {
		$this->assertEquals('"HTML"', $this->Essence->replace('"http://pass.com"'));
	}
}
