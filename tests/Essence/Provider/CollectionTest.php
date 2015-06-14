<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Di\Container;
use StdClass;



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
		$this->Provider = new StdClass();

		$Container = new Container();
		$Container->set('Foo', $this->Provider);
		$Container->set('filters', [
			'Foo' => '~^foo$~',
			'Bar' => function ($url) {
				return ($url === 'bar');
			}
		]);

		$this->Collection = new Collection($Container);
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
