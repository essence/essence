<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Json.
 */
class JsonTest extends TestCase {

	/**
	 *
	 */
	public $valid = <<<VALID
		{
			"title": "Title",
			"type": "video"
		}
VALID;

	/**
	 *
	 */
	public $invalid = <<<VALID
		{
			"title" "Title",
			"type": "video"
VALID;



	/**
	 *
	 */
	public function testParse() {
		$this->assertEquals([
			'title' => 'Title',
			'type' => 'video'
		], Json::parse($this->valid));
	}



	/**
	 *
	 */
	public function testParseInvalid() {
		$this->setExpectedException('Exception');
		Json::parse($this->invalid);
	}
}
