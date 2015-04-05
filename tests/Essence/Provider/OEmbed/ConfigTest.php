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
	public $Config = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Config = new Config();
	}



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
		$this->Config->setEndpoint('endpoint');
		$this->assertEquals('endpoint', $this->Config->endpoint());
	}



	/**
	 *
	 */
	public function testFormat() {
		$this->Config->setFormat('format');
		$this->assertEquals('format', $this->Config->format());
	}



	/**
	 *
	 */
	public function testCompleteEndpoint() {
		$this->Config->setEndpoint('url');
		$this->Config->completeEndpoint([
			'maxwidth' => 120,
			'maxheight' => 60
		]);

		$this->assertEquals(
			'url?maxwidth=120&maxheight=60',
			$this->Config->endpoint()
		);
	}



	/**
	 *
	 */
	public function testCompleteEndpointWithParameters() {
		$this->Config->setEndpoint('url?param=value');
		$this->Config->completeEndpoint([
			'maxwidth' => 120
		]);

		$this->assertEquals(
			'url?param=value&maxwidth=120',
			$this->Config->endpoint()
		);
	}
}
