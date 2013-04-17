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
 *	Test case for Ted.
 */

class CollegeHumourExtTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $CollegeHumourExt = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->CollegeHumourExt = new CollegeHumourExt( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue( $this->CollegeHumourExt->canEmbed( 'http://www.collegehumor.com/article/6884402/obituaries-for-the-stuff-you-should-throw-out' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse( $this->CollegeHumourExt->canEmbed( 'http://www.unsupported.com' ));
	}
}
