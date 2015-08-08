<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Media;



/**
 *	Test case for Presenter.
 */
class PresenterTest extends TestCase {

	/**
	 *
	 */
	public function testInvoke() {
		$media = new Media([
			'title' => 'a'
		]);

		$expected = new Media([
			'title' => 'b'
		]);

		$Presenter = $this->getMockForAbstractClass(
			'\\Essence\\Provider\\Presenter'
		);

		$Presenter
			->expects($this->exactly(1))
			->method('present')
			->with($this->identicalTo($media))
			->will($this->returnValue($expected));

		$this->assertEquals($expected, $Presenter($media));
	}
}
