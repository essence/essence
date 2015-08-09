<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Provider.
 */
class ProviderTest extends TestCase {

	/**
	 *
	 */
	public $Provider = null;
	public $Media = null;



	/**
	 *
	 */
	public function setup() {
		$this->Media = new Media([
			'url' => 'http://foo.bar.com/resource',
			'title' => 'Title',
			'description' => 'Description',
			'width' => 800,
			'height' => 600
		]);

		$this->Provider = $this->getMockForAbstractClass(
			'\\Essence\\Provider'
		);
	}



	/**
	 *
	 */
	public function testExtract() {
		$url = 'http://foo.bar';
		$options = [
			'foo' => 'bar'
		];

		$prepare = function($url) {
			return 'prepared' . $url;
		};

		$present = function($Media) {
			return $Media->set('foo', 'bar');
		};

		$this->Provider->setPreparators([$prepare]);
		$this->Provider->setPresenters([$present]);

		$this->Provider
			->expects($this->once())
			->method('_extract')
			->with(
				$this->equalTo($prepare($url)),
				$this->equalTo($options)
			)
			->will($this->returnValue($this->Media));

		$this->assertEquals(
			$present($this->Media),
			$this->Provider->extract($url, $options)
		);
	}
}
