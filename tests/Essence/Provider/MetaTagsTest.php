<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Dom\Parser\Native as NativeDomParser;
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
			new NativeDomParser()
		);
	}



	/**
	 *
	 */
	public function testEmbed() {
		$Media = $this->MetaTags->embed(
			'file://' . ESSENCE_HTTP . 'valid.html'
		);

		$this->assertEquals('YouTube', $Media->get('og:site_name'));
	}
}
