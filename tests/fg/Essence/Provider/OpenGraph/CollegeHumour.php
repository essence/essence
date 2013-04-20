<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@author Laughingwithu <Laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for CollegeHumour.
 */

class CollegeHumourTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $CollegeHumour = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->CollegeHumour = new CollegeHumour( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue( $this->CollegeHumour->canEmbed( 'http://www.collegehumor.com/article/6884402/obituaries-for-the-stuff-you-should-throw-out' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse( $this->CollegeHumour->canEmbed( 'http://www.collegehumor.com/embed/6884604/how-to-break-out-of-zip-ties' ));
	}
}
