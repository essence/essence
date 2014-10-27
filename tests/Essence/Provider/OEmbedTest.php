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
	public $Http = null;



	/**
	 *
	 */
	public $OEmbed = null;



	/**
	 *
	 */
	public function setup() {
		$this->Http = $this->getMock('Essence\\Http\\Client\\Native');

		$this->OEmbed = new OEmbed(
			new NativeHttpClient(),
			new NativeDomDocument()
		);

		$this->OEmbed->configure([
			'endpoint' => 'file://' . ESSENCE_HTTP . ':url.json',
			'format' => Format::json
		]);
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
		$this->setExpectedException('Essence\\Exception');
		$this->OEmbed->embed('invalid');
	}



	/**
	 *
	 */
	public function testEmbedXml() {
		$this->OEmbed->set('endpoint', 'file://' . ESSENCE_HTTP . ':url.xml');
		$this->OEmbed->set('format', Format::xml);

		$this->assertNotNull($this->OEmbed->embed('valid'));
	}



	/**
	 *
	 */
	public function testEmbedInvalidXml() {
		$this->OEmbed->set('endpoint', 'file://' . ESSENCE_HTTP . ':url.xml');
		$this->OEmbed->set('format', Format::xml);

		$this->setExpectedException('Essence\\Exception');
		$this->OEmbed->embed('invalid');
	}



	/**
	 *
	 */
	public function testEmbedUnsupportedFormat() {
		$this->OEmbed->set('format', 'unsupported');

		$this->setExpectedException('Essence\\Exception');
		$this->OEmbed->embed('valid');
	}



	/**
	 *
	 */
	public function testEmbedGeneric() {
		$this->OEmbed->set('endpoint', '');

		$this->assertNotNull(
			$this->OEmbed->embed('file://' . ESSENCE_HTTP . 'valid.html')
		);
	}
}
