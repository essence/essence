<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_Constraint_IsEqual as IsEqual;
use Essence\Media;
use Essence\Http\Client\Native as NativeHttpClient;
use Essence\Utility\Url;



/**
 *	Test case for Essence.
 *	This test is just plain stupid.
 */
class EssenceTest extends TestCase {

	/**
	 *
	 */
	public $Essence = null;



	/**
	 *
	 */
	public function isEqual($value) {
		return new IsEqual($value);
	}



	/**
	 *
	 */
	public function testContainer() {
		$Essence = new Essence([
			'foo' => 'bar'
		]);

		$Container = $Essence->container();

		$this->assertInstanceOf('\\Essence\\Di\\Container', $Container);
		$this->assertEquals('bar', $Container->get('foo'));
	}



	/**
	 *
	 */
	public function testCrawl() {
		$source = 'source';
		$urls = [];

		$Crawler = $this->getMockBuilder('\\Essence\\Crawler')
			->disableOriginalConstructor()
			->getMock();

		$Crawler
			->expects($this->once())
			->method('crawl')
			->with($this->isEqual($source))
			->will($this->returnValue($urls));

		$Essence = new Essence([
			'Crawler' => $Crawler
		]);

		$this->assertEquals($urls, $Essence->crawl($source));
	}



	/**
	 *
	 */
	public function testCrawlUrl() {
		$url = 'http://test.com';
		$source = 'source';
		$urls = [
			'/index.php'
		];

		$Http = $this->getMock('\\Essence\\Http\\Client');

		$Http
			->expects($this->once())
			->method('get')
			->with($this->isEqual($url))
			->will($this->returnValue($source));

		$Crawler = $this->getMockBuilder('\\Essence\\Crawler')
			->disableOriginalConstructor()
			->getMock();

		$Crawler
			->expects($this->once())
			->method('crawl')
			->with($this->isEqual($source))
			->will($this->returnValue($urls));

		$Essence = new Essence([
			'Http' => $Http,
			'Crawler' => $Crawler
		]);

		$this->assertEquals(
			Url::resolveAll($urls, $url),
			$Essence->crawlUrl($url)
		);
	}



	/**
	 *
	 */
	public function testExtract() {
		$url = 'url';
		$options = [];
		$Media = new Media();

		$Extractor = $this->getMockBuilder('\\Essence\\Extractor')
			->disableOriginalConstructor()
			->getMock();

		$Extractor
			->expects($this->once())
			->method('extract')
			->with($this->isEqual($url), $this->isEqual($options))
			->will($this->returnValue($Media));

		$Essence = new Essence([
			'Extractor' => $Extractor
		]);

		$this->assertEquals($Media, $Essence->extract($url, $options));
	}



	/**
	 *
	 */
	public function testExtractAll() {
		$urls = ['url'];
		$options = [];
		$medias = [new Media()];

		$Extractor = $this->getMockBuilder('\\Essence\\Extractor')
			->disableOriginalConstructor()
			->getMock();

		$Extractor
			->expects($this->once())
			->method('extractAll')
			->with($this->isEqual($urls), $this->isEqual($options))
			->will($this->returnValue($medias));

		$Essence = new Essence([
			'Extractor' => $Extractor
		]);

		$this->assertEquals($medias, $Essence->extractAll($urls, $options));
	}



	/**
	 *
	 */
	public function testReplace() {
		$text = 'text';
		$template = function() {};
		$options = [];
		$replaced = 'replaced text';

		$Replacer = $this->getMockBuilder('\\Essence\\Replacer')
			->disableOriginalConstructor()
			->getMock();

		$Replacer
			->expects($this->once())
			->method('replace')
			->with(
				$this->isEqual($text),
				$this->isEqual($template),
				$this->isEqual($options)
			)
			->will($this->returnValue($replaced));

		$Essence = new Essence([
			'Replacer' => $Replacer
		]);

		$this->assertEquals(
			$replaced,
			$Essence->replace($text, $template, $options)
		);
	}
}
