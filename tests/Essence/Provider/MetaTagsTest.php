<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Dom\Document\Factory\Native as NativeDomDocument;
use Essence\Http\Client\Native as NativeHttpClient;



/**
 *	Test case for MetaTags.
 */
class MetaTagsTest extends TestCase {

	/**
	 *
	 */
	public $MetaTags = null;



	/**
	 *
	 */
	public function setUp() {
		$this->MetaTags = new MetaTags(
			new NativeHttpClient(),
			new NativeDomDocument()
		);

		$this->MetaTags->setMetaPattern('~^og:~');
	}



	/**
	 *
	 */
	public function testExtract() {
		$Media = $this->MetaTags->extract(
			'file://' . ESSENCE_HTTP . 'valid.html'
		);

		$this->assertEquals('YouTube', $Media->get('og:site_name'));
		$this->assertFalse($Media->has('twitter:site'));
	}



	/**
	 *
	 */
	public function testExtractNothing() {
		$this->setExpectedException('Exception');

		$this->MetaTags->setMetaPattern('~nothing~');
		$this->MetaTags->extract(
			'file://' . ESSENCE_HTTP . 'valid.html'
		);
	}
}
