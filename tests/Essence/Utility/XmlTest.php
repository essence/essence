<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;

use PHPUnit\Framework\TestCase as TestCase;



/**
 *	Test case for Xml.
 */
class XmlTest extends TestCase {

	/**
	 *
	 */
	public $valid =
		'<?xml version="1.0" encoding="utf-8"?>
		<oembed>
			<title>Title</title>
			<type>video</type>
		</oembed>';



	/**
	 *
	 */
	public $invalid =
		'<oembed>
			<title>Title
			<type>video</type>';



	/**
	 *
	 */
	public function testParse() {
		$this->assertEquals([
			'title' => 'Title',
			'type' => 'video'
		], Xml::parse($this->valid));
	}



	/**
	 *
	 */
	public function testParseInvalid() {
		$this->expectException('Exception');
		Xml::parse($this->invalid);
	}
}
