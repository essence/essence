<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Di\Container\Standard as StandardContainer;



/**
 *	Test case for Replacer.
 */
class ReplacerTest extends TestCase {

	/**
	 *
	 */
	public $Replacer = null;



	/**
	 *
	 */
	public function setUp() {
		$Media = new Media([
			'title' => 'Title',
			'html' => 'HTML'
		]);

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

		$this->Replacer = new Replacer($Container->get('Extractor'));
	}



	/**
	 *
	 */
	public function testReplace() {
		$this->assertEquals(
			'foo HTML bar',
			$this->Replacer->replace('foo http://pass.example.com bar')
		);
	}



	/**
	 *
	 */
	public function testReplaceSingleUrl() {
		$this->assertEquals(
			'HTML',
			$this->Replacer->replace('http://pass.example.com')
		);
	}



	/**
	 *
	 */
	public function testReplaceTagSurroundedUrl() {
		$this->assertEquals(
			'<span>HTML</span>',
			$this->Replacer->replace('<span>http://pass.example.com</span>')
		);
	}



	/**
	 *
	 */
	public function testReplaceWithTemplate() {
		$this->assertEquals(
			'foo <h1>Title</h1> bar',
			$this->Replacer->replace('foo http://pass.example.com bar', function($Media) {
				return '<h1>' . $Media->title . '</h1>';
			})
		);
	}



	/**
	 *
	 */
	public function testDontReplaceLinks() {
		$link = '<a href="http://pass.com">baz</a>';
		$this->assertEquals($link, $this->Replacer->replace($link));

		$link = '<a href="http://www.pass.com/watch?v=emgJtr9tIME">baz</a>';
		$this->assertEquals($link, $this->Replacer->replace($link));

		$link = "<a href='http://pass.com'>baz</a>";
		$this->assertEquals($link, $this->Replacer->replace($link));
	}



	/**
	 *
	 */
	public function testReplaceQuotesSurroundedUrl() {
		$this->assertEquals('"HTML"', $this->Replacer->replace('"http://pass.com"'));
	}
}
