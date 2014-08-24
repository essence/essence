<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Presenter;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Media;



/**
 *	Test case for Templater.
 */
class TemplaterTest extends TestCase {

	/**
	 *
	 */
	public $Templater = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Templater = new Templater('html', 'type', [
			'#foo#' => ':url',
			'#.*#' => 'default'
		]);
	}



	/**
	 *
	 */
	public function testFilter() {
		$Media = $this->Templater->filter(new Media([
			'url' => 'url',
			'type' => 'foo'
		]));

		$this->assertEquals('url', $Media->get('html'));

		$Media = $this->Templater->filter(new Media([
			'type' => 'bar'
		]));

		$this->assertEquals('default', $Media->get('html'));
	}
}
