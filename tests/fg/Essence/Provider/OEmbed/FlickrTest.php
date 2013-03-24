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
 *	Test case for Flickr.
 */

class FlickrTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Flickr = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Flickr = new Flickr( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue(
			$this->Flickr->canEmbed( 'http://www.flickr.com/photos/phuson/4311168776/' )
		);

		$this->assertTrue(
			$this->Flickr->canEmbed( 'http://www.flickr.com/photos/20916857@N00/2934150415' )
		);
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse(
			$this->Flickr->canEmbed( 'http://www.flickr.com/tour' )
		);
	}
}
