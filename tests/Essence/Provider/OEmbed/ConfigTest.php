<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\OEmbed;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *
 */
class ConfigTest extends TestCase {

	/**
	 *
	 */
	public function testConstruct() {
		$Config = new Config('endpoint', 'format');

		$this->assertEquals('endpoint', $Config->endpoint());
		$this->assertEquals('format', $Config->format());
	}



	/**
	 *
	 */
	public function testEndpoint() {
		$Config = new Config();
		$Config->setEndpoint('endpoint');

		$this->assertEquals('endpoint', $Config->endpoint());
	}



	/**
	 *
	 */
	public function testFormat() {
		$Config = new Config();
		$Config->setFormat('format');

		$this->assertEquals('format', $Config->format());
	}
}
