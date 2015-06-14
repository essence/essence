<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Media.
 */
class MediaTest extends TestCase {

	/**
	 *
	 */
	public $properties = [
		'one' => 1,
		'two' => 2
	];



	/**
	 *
	 */
	public $Media = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Media = new Media($this->properties);
	}



	/**
	 *
	 */
	public function testMagicIsSet() {
		$this->assertTrue(isset($this->Media->one));
		$this->assertFalse(isset($this->Media->unset));
	}



	/**
	 *
	 */
	public function testMagicGet() {
		$this->assertEquals(1, $this->Media->one);
	}



	/**
	 *
	 */
	public function testMagicSet() {
		$this->Media->foo = 'bar';
		$this->assertEquals('bar', $this->Media->foo);
	}



	/**
	 *
	 */
	public function testHas() {
		$this->assertTrue($this->Media->has('one'));
		$this->assertFalse($this->Media->has('unset'));
	}



	/**
	 *
	 */
	public function testGet() {
		$this->assertEquals(1, $this->Media->get('one'));
		$this->assertEmpty($this->Media->get('unset'));
	}



	/**
	 *
	 */
	public function testSet() {
		$ref = $this->Media->set('foo', 'bar');

		$this->assertEquals('bar', $this->Media->foo);
		$this->assertEquals($this->Media, $ref);
	}



	/**
	 *
	 */
	public function testSetDefault() {
		$this->Media->setDefault('one', 2);
		$this->assertEquals(1, $this->Media->get('one'));

		$ref = $this->Media->setDefault('three', 3);
		$this->assertEquals(3, $this->Media->get('three'));
		$this->assertEquals($this->Media, $ref);
	}



	/**
	 *
	 */
	public function testSetDefaults() {
		$ref = $this->Media->setDefaults([
			'one' => 2,
			'three' => 3
		]);

		$this->assertEquals(1, $this->Media->get('one'));
		$this->assertEquals(3, $this->Media->get('three'));
		$this->assertEquals($this->Media, $ref);
	}



	/**
	 *
	 */
	public function testProperties() {
		$properties = [
			'two' => 2
		];

		$ref = $this->Media->setProperties($properties);

		$this->assertEquals($properties, $this->Media->properties());
		$this->assertEquals(2, $this->Media->get('two'));
		$this->assertFalse($this->Media->has('one'));
		$this->assertEquals($this->Media, $ref);
	}



	/**
	 *
	 */
	public function testFilledProperties() {
		$properties = [
			'empty' => '',
			'nonEmpty' => 'filled'
		];

		$this->Media->setProperties($properties);

		$this->assertEquals(
			array_filter($properties),
			$this->Media->filledProperties()
		);
	}



	/**
	 *
	 */
	public function testConfigure() {
		$ref = $this->Media->configure([
			'foo' => 'bar'
		]);

		$this->assertEquals('bar', $this->Media->get('foo'));
		$this->assertEquals($this->Media, $ref);
	}



	/**
	 *
	 */
	public function testIterator() {
		$properties = [];

		foreach ($this->Media as $property => $value) {
			$properties[$property] = $value;
		}

		$this->assertEquals($properties, $this->Media->properties());
	}



	/**
	 *
	 */
	public function testSerialize() {
		$this->assertEquals(
			json_encode($this->Media->properties()),
			json_encode($this->Media)
		);
	}
}
