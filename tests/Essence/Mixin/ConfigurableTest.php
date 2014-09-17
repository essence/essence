<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Mixin;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *
 */
class ConfigurableImplementation {

	use Configurable;



	/**
	 *
	 */
	protected $_properties = [
		'one' => 1,
		'two' => 2
	];
}



/**
 *	Test case for Configurable.
 */
class ConfigurableTest extends TestCase {

	/**
	 *
	 */
	public $Configurable = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Configurable = new ConfigurableImplementation();
	}



	/**
	 *
	 */
	public function testMagicIsSet() {
		$this->assertTrue(isset($this->Configurable->one));
		$this->assertFalse(isset($this->Configurable->unset));
	}



	/**
	 *
	 */
	public function testMagicGet() {
		$this->assertEquals(1, $this->Configurable->one);
	}



	/**
	 *
	 */
	public function testMagicSet() {
		$this->Configurable->foo = 'bar';
		$this->assertEquals('bar', $this->Configurable->foo);
	}



	/**
	 *
	 */
	public function testHas() {
		$this->assertTrue($this->Configurable->has('one'));
		$this->assertFalse($this->Configurable->has('unset'));
	}



	/**
	 *
	 */
	public function testGet() {
		$this->assertEquals(1, $this->Configurable->get('one'));
		$this->assertEmpty($this->Configurable->get('unset'));
	}



	/**
	 *
	 */
	public function testSet() {
		$this->Configurable->set('foo', 'bar');
		$this->assertEquals('bar', $this->Configurable->foo);

		$this->assertEquals(
			$this->Configurable,
			$this->Configurable->set('', '')
		);
	}



	/**
	 *
	 */
	public function testSetDefault() {
		$this->Configurable->setDefault('one', 2);
		$this->assertEquals(1, $this->Configurable->get('one'));

		$this->Configurable->setDefault('three', 3);
		$this->assertEquals(3, $this->Configurable->get('three'));

		$this->assertEquals(
			$this->Configurable,
			$this->Configurable->setDefault('', '')
		);
	}



	/**
	 *
	 */
	public function testSetDefaults() {
		$this->Configurable->setDefaults([
			'one' => 2,
			'three' => 3
		]);

		$this->assertEquals(1, $this->Configurable->get('one'));
		$this->assertEquals(3, $this->Configurable->get('three'));

		$this->assertEquals(
			$this->Configurable,
			$this->Configurable->setDefaults([])
		);
	}



	/**
	 *
	 */
	public function testProperties() {
		$properties = [
			'two' => 2
		];

		$this->Configurable->setProperties($properties);

		$this->assertEquals($properties, $this->Configurable->properties());
		$this->assertEquals(2, $this->Configurable->get('two'));
		$this->assertFalse($this->Configurable->has('one'));

		$this->assertEquals(
			$this->Configurable,
			$this->Configurable->setProperties([])
		);
	}



	/**
	 *
	 */
	public function testConfigure() {
		$this->Configurable->configure([
			'one' => 2
		]);

		$this->assertEquals(2, $this->Configurable->get('one'));
		$this->assertEquals(2, $this->Configurable->get('two'));

		$this->assertEquals(
			$this->Configurable,
			$this->Configurable->configure([])
		);
	}
}
