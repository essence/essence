<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Preparator.
 */
class PreparatorTest extends TestCase {

	/**
	 *
	 */
	public function testInvoke() {
		$url = 'a';
		$expected = 'b';

		$Preparator = $this->getMockForAbstractClass(
			'\\Essence\\Provider\\Preparator'
		);

		$Preparator
			->expects($this->exactly(1))
			->method('prepare')
			->with($this->identicalTo($url))
			->will($this->returnValue($expected));

		$this->assertEquals($expected, $Preparator($url));
	}
}
