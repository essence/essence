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
	public function testEmbedJson() {
		$this->assertNotNull($this->OEmbed->embed('valid'));
	}



	/**
	 *
	 */
	public function testEmbedInvalidJson() {
		$this->setExpectedException('Exception');
		$this->OEmbed->embed('invalid');
	}



	/**
	 *
	 */
	public function testEmbedXml() {
		$this->OEmbed->setEndpoint('file://' . ESSENCE_HTTP . ':url.xml');
		$this->OEmbed->setFormat(Format::xml);

		$this->assertNotNull($this->OEmbed->embed('valid'));
	}



	/**
	 *
	 */
	public function testEmbedInvalidXml() {
		$this->OEmbed->setEndpoint('file://' . ESSENCE_HTTP . ':url.xml');
		$this->OEmbed->setFormat(Format::xml);

		$this->setExpectedException('Exception');
		$this->OEmbed->embed('invalid');
	}



	/**
	 *
	 */
	public function testEmbedUnsupportedFormat() {
		$this->OEmbed->setFormat('unsupported');

		$this->setExpectedException('Exception');
		$this->OEmbed->embed('valid');
	}



	/**
	 *
	 */
	public function testEmbedGeneric() {
		$this->OEmbed->setEndpoint('');

		$this->assertNotNull(
			$this->OEmbed->embed('file://' . ESSENCE_HTTP . 'valid.html')
		);
	}
}
