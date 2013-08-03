<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Utility;

use PHPUnit_Framework_TestCase;



/**
 *	Test case for Hash.
 */

class HashTest {

	/**
	 *
	 */

	public function testReindex( ) {

		$data = Hash::reindex(
			array( 'one' => 'value' ),
			array( 'one' => 'two' )
		);

		$this->assertEquals(
			array( 'two' => 'value' ),
			$data
		);
	}



	/**
	 *
	 */

	public function testNormalize( ) {

		$data = Hash::normalize(
			array(
				'one',
				'two' => 'three',
				'four'
			),
			'default'
		);

		$this->assertEquals(
			array(
				'one' => 'default',
				'two' => 'three',
				'four' => 'default'
			),
			$data
		);
	}
}
