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
		], $data);
	}



	/**
	 *
	 */
	public function testCombine() {
		$data = [
			['key' => 1, 'value' => 'one'],
			['key' => 2, 'value' => 'two']
		];

		$combined = Hash::combine($data, function($row) {
			yield $row['key'] => $row['value'];
		});

		$this->assertEquals([
			1 => 'one',
			2 => 'two'
		], $combined);
	}
}
