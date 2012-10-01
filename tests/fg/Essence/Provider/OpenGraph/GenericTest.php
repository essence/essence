<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Generic.
 */

class GenericTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Generic = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Generic = new Generic( );
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$this->assertNotNull(
			$this->Generic->embed( 'file://' . ESSENCE_HTTP . 'valid.html' )
		);
	}
}
