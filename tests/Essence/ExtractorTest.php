<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Di\Container\Standard as StandardContainer;



/**
 *	Test case for Extractor.
 */
class ExtractorTest extends TestCase {

	/**
	 *
	 */
	public $Extractor = null;



	/**
	 *
	 */
	public function setUp() {
		$Media = new Media();
		$Provider = $this->getMockForAbstractClass('\\Essence\\Provider');

		$Provider
			->expects($this->any())
			->method('_extract')
			->will($this->returnValue($Media));

		$Container = new StandardContainer();
		$Container->set('Provider', $Provider);
		$Container->set('filters', [
			'Provider' => '~pass~i'
		]);

		$this->Extractor = new Extractor($Container->get('Collection'));
	}



	/**
	 *
	 */
	public function testExtract() {
		$this->assertNotNull($this->Extractor->extract('http://pass.foo.com/bar'));
	}



	/**
	 *
	 */
	public function testExtractAll() {
		$urls = ['one', 'two'];
		$medias = $this->Extractor->extractAll($urls);

		$this->assertEquals($urls, array_keys($medias));
	}
}
