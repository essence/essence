<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Di;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *
 */
class Containable { }



/**
 *	Test case for Container.
 */
class ContainerTest extends TestCase {

	/**
	 *
	 */
	public $Container = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Container = new Container();
	}



	/**
	 *
	 */
	public function testGet() {
		$this->Container->set('integer', 12);
		$this->assertEquals(12, $this->Container->get('integer'));
	}



	/**
	 *
	 */
	public function testGetClosureResult() {
		$this->Container->set('Containable', function($Container) {
			return new Containable();
		});

		$this->assertEquals(new Containable(), $this->Container->get('Containable'));
		$this->assertNotSame(new Containable(), $this->Container->get('Containable'));
	}



	/**
	 *
	 */
	public function testGetUnique() {
		$unique = Container::unique(function($Container) {
			return new Containable();
		});

		$first = $unique($this->Container);
		$second = $unique($this->Container);

		$this->assertSame($first, $second);
	}



	/**
	 *
	 */
	public function testSet() {
		$ref = $this->Container->set('foo', 'bar');

		$this->assertEquals('bar', $this->Container->get('foo'));
		$this->assertEquals($this->Container, $ref);
	}



	/**
	 *
	 */
	public function testConfigure() {
		$ref = $this->Container->configure([
			'foo' => 'bar'
		]);

		$this->assertEquals('bar', $this->Container->get('foo'));
		$this->assertEquals($this->Container, $ref);
	}
}
