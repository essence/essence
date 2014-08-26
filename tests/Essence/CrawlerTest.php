<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Di\Container\Standard as StandardContainer;
use Essence\Dom\Parser\Native as NativeDomParser;
use Essence\Http\Client\Native as NativeHttpClient;



/**
 *	Test case for Crawler.
 */
class CrawlerTest extends TestCase {

	/**
	 *
	 */
	public $Crawler = null;



	/**
	 *
	 */
	public function setUp() {
		$Container = new StandardContainer([
			'Collection.providers' => [
				'provider' => [
					'class' => 'Provider',
					'filter' => '#pass#i'
				]
			]
		]);

		$this->Crawler = new Crawler(
			$Container->get('Collection'),
			new NativeHttpClient(),
			new NativeDomParser()
		);
	}



	/**
	 *
	 */
	public function testCrawlPage() {
		$this->assertEquals([
			'http://pass.foo.com',
			'http://pass.embed.com',
			'http://pass.iframe.com'
		], $this->Crawler->crawl('file://' . ESSENCE_HTTP . 'valid.html'));
	}



	/**
	 *
	 */
	public function testCrawlSource() {
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
		], $this->Crawler->crawl($html));
	}
}
