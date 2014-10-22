<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom\Tag;

use PHPUnit_Framework_TestCase as TestCase;
use DOMDocument;



/**
 *	Test case for Native.
 */
class NativeTest extends TestCase {

	/**
	 *
	 */
	public $Element = null;



	/**
	 *
	 */
	public $Tag = null;



	/**
	 *
	 */
	public function setUp() {
		$Document = new DOMDocument();

		$this->Element = $Document->createElement('element');
		$this->Element->setAttribute('foo', 'bar');

		$this->Tag = new Native($this->Element);
	}



	/**
	 *
	 */
	public function testGet() {
		$this->assertEquals(
			$this->Element->getAttribute('foo'),
			$this->Tag->get('foo')
		);
	}



	/**
	 *
	 */
	public function testGetDefault() {
		$default = 'default';

		$this->assertEquals(
			$default,
			$this->Tag->get('unset', $default)
		);
	}
}
