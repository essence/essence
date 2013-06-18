<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Utility;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Set.
 */

class SetTest {

	/**
	 *
	 */

	public function testReindex( ) {

		$data = Set::reindex(
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

		$data = Set::normalize(
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
