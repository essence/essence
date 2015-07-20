<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Url.
 */
class UrlTest extends TestCase {

	/**
	 *
	 */
	public function testResolveFullyQualified() {
		$this->assertEquals(
			'http://test.com/a',
			Url::resolve('http://test.com/a', 'test.com')
		);

		$this->assertEquals(
			'https://test.com/a',
			Url::resolve('//test.com/a', 'https://test.com')
		);
	}



	/**
	 *
	 */
	public function testResolveAbsolute() {
		$this->assertEquals(
			'http://test.com/a',
			Url::resolve('/a', 'http://test.com/a/b')
		);
	}



	/**
	 *
	 */
	public function testResolveRelative() {
		$this->assertEquals(
			'http://test.com/b',
			Url::resolve('b', 'http://test.com/a')
		);

		$this->assertEquals(
			'http://test.com/a/d/f',
			Url::resolve('../d/./e/../f', 'http://test.com/a/b/c')
		);
	}



	/**
	 *
	 */
	public function testResolvePath() {
		$this->assertEquals(
			'/a/c',
			Url::resolvePath('c', '/a/b')
		);

		$this->assertEquals(
			'/a/d/f',
			Url::resolvePath('../d/./e/../f', '/a/b/c')
		);
	}



	/**
	 *
	 */
	public function testSplitPath() {
		$this->assertEquals(
			['a', '..', 'b'],
			array_values(Url::splitPath('./a/../b'))
		);
	}



	/**
	 *
	 */
	public function testHost() {
		$parts = [
			Url::scheme => 'http',
			Url::host => 'test.com',
			Url::port => '8080',
			Url::user => 'user',
			Url::pass => 'pass'
		];

		$this->assertEquals(
			'http://user:pass@test.com:8080',
			Url::host($parts)
		);
	}



	/**
	 *
	 */
	public function testPath() {
		$parts = [
			Url::path => 'a',
			Url::query => 'b=c',
			Url::fragment => 'd'
		];

		$this->assertEquals('/a?b=c#d', Url::path($parts));
	}



	/**
	 *
	 */
	public function testResolveAll() {
		$urls = [
			'/home',
			'../home'
		];

		$expected = [
			'http://test.com/home'
		];

		$this->assertEquals(
			$expected,
			Url::resolveAll($urls, 'http://test.com')
		);
	}
}
