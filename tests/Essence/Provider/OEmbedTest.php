<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Provider\OEmbed\Format;
use Essence\Dom\Document\Factory\Native as NativeDomDocument;
use Essence\Http\Client\Native as NativeHttpClient;



/**
 *	Test case for OEmbed.
 */
class OEmbedTest extends TestCase {

	/**
	 *
	 */
	public $OEmbed = null;



	/**
	 *
	 */
	public function setup() {
		$this->OEmbed = new OEmbed(
			new NativeHttpClient(),
			new NativeDomDocument()
		);

		$this->OEmbed->setEndpoint('file://' . ESSENCE_HTTP . ':url.json');
	}



	/**
	 *
	 */
	public function testExtractJson() {
		$this->assertNotNull($this->OEmbed->extract('valid'));
	}



	/**
	 *
	 */
	public function testExtractInvalidJson() {
		$this->setExpectedException('Exception');
		$this->OEmbed->extract('invalid');
	}



	/**
	 *
	 */
	public function testExtractXml() {
		$this->OEmbed->setEndpoint('file://' . ESSENCE_HTTP . ':url.xml');
		$this->OEmbed->setFormat(Format::xml);

		$this->assertNotNull($this->OEmbed->extract('valid'));
	}



	/**
	 *
	 */
	public function testExtractInvalidXml() {
		$this->OEmbed->setEndpoint('file://' . ESSENCE_HTTP . ':url.xml');
		$this->OEmbed->setFormat(Format::xml);

		$this->setExpectedException('Exception');
		$this->OEmbed->extract('invalid');
	}



	/**
	 *
	 */
	public function testExtractUnsupportedFormat() {
		$this->OEmbed->setFormat('unsupported');

		$this->setExpectedException('Exception');
		$this->OEmbed->extract('valid');
	}



	/**
	 *
	 */
	public function testExtractGeneric() {
		$this->OEmbed->setEndpoint('');

		$this->assertNotNull(
			$this->OEmbed->extract('file://' . ESSENCE_HTTP . 'valid.html')
		);
	}
}
