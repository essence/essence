<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Hash.
 */
class HashTest extends TestCase {

	/**
	 *
	 */
	public function testReindex() {
		$data = Hash::reindex(
			['one' => 'value'],
			['one' => 'two']
		);

		$this->assertEquals([
			'one' => 'value',
			'two' => 'value'
		], $data );
	}



	/**
	 *
	 */
	public function testNormalize() {
		$data = Hash::normalize([
			'one',
			'two' => 'three',
			'four'
		], 'default' );

		$this->assertEquals([
			'one' => 'default',
			'two' => 'three',
			'four' => 'default'
		], $data );
	}
}
