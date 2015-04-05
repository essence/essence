<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use PHPUnit_Framework_TestCase as TestCase;
use Exception as NativeException;



/**
 *	Test case for Exception.
 */
class ExceptionTest extends TestCase {

	/**
	 *
	 */
	public function testWrap() {
		$Native = new NativeException('message', 12);
		$Exception = Exception::wrap($Native);

		$this->assertEquals(
			$Native->getMessage(),
			$Exception->getMessage()
		);

		$this->assertEquals(
			$Native->getCode(),
			$Exception->getCode()
		);

		$this->assertEquals(
			$Native,
			$Exception->getPrevious()
		);
	}
}
