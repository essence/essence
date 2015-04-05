<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Provider;
use Essence\Provider\OEmbed;
use Essence\Di\Container;



/**
 *
 */
class ProviderImplementation extends Provider {

	/**
	 *
	 */
	protected function _embed($url, array $options) {}

}



/**
 *	Test case for Collection.
 */
class CollectionTest extends TestCase {

	/**
	 *
	 */
	public $Provider = null;



	/**
	 *
	 */
	public $Collection = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Provider = new ProviderImplementation();

		$Container = new Container();
		$Container->set('OEmbed', $this->Provider);

		$this->Collection = new Collection($Container);
		$this->Collection->setProperties([
			'Foo' => [
				'class' => 'OEmbed',
				'filter' => '#^foo$#'
			],
			'Bar' => [
				'class' => 'OpenGraph',
				'filter' => function ($url) {
					return ($url === 'bar');
				}
			]
		]);
	}



	/**
	 *
	 */
	public function testHasProvider() {
		$this->assertTrue($this->Collection->hasProvider('foo'));
		$this->assertTrue($this->Collection->hasProvider('bar'));
		$this->assertFalse($this->Collection->hasProvider('baz'));
	}



	/**
	 *
	 */
	public function testProviders() {
		$providers = [];

		foreach ($this->Collection->providers('foo') as $Provider) {
			$providers[] = $Provider;
		}

		$this->assertEquals([$this->Provider], $providers);
	}
}
