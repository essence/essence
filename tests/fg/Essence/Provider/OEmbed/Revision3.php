http://revision3.com/technobuffalo/rumor-roundup-355-iring-apple-tv
<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Hulu.
 */

class HuluTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Hulu = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Hulu = new Hulu( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue(
			$this->Hulu->canEmbed( 'http://revision3.com/coucou/tu-veux-voir-ma-b' )
		);
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse(
			$this->Hulu->canEmbed( 'http://revision3.com/networks' )
		);
	}
}
