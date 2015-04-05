<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom\Document;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Native.
 */
class NativeTest extends TestCase {

	/**
	 *
	 */
	public $Document = null;



	/**
	 *
	 */
	public $html = <<<HTML
		<a href="http://www.test.com" title="Link">
		<a href="http://www.othertest.com" title="Other link" target="_blank">
HTML;



	/**
	 *
	 */
	public function setUp() {
		$this->Document = new Native($this->html);
	}



	/**
	 *
	 */
	public function testConstruct() {
		$this->setExpectedException('Essence\Exception');
		$Native = new Native('');
	}



	/**
	 *
	 */
	public function testTags() {
		$tags = $this->Document->tags('a');

		$titles = array_map(function($Tag) {
			return $Tag->get('title');
		}, $tags);

		$this->assertEquals([
			'Link',
			'Other link'
		], $titles);
	}



	/**
	 *
	 */
	public function testTagsWithFilter() {
		$tags = $this->Document->tags('a', function($Tag) {
			return $Tag->get('target') === '_blank';
		});

		$titles = array_map(function($Tag) {
			return $Tag->get('title');
		}, $tags);

		$this->assertEquals(['Other link'], $titles);
	}
}
