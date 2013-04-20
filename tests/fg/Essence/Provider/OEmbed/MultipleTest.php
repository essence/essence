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
 *	Test case for Multiple.
 */

class MultipleTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Multiple = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Multiple = new Multiple( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		// 23hq

		// Cacoo

		// Chirb.it

		// Clikthrough

		// CollegeHumour

		// Deviantart

		// Dipity

		// Flickr
		$this->assertTrue( $this->Multiple->canEmbed( 'http://www.flickr.com/photos/phuson/4311168776/' ));
		$this->assertTrue( $this->Multiple->canEmbed( 'http://www.flickr.com/photos/20916857@N00/2934150415' ));

		// Funnyordie

		// Huffduffer

		// Hulu
		$this->assertTrue( $this->Multiple->canEmbed( 'http://www.hulu.com/watch/2183' ));

		// Ifixit

		// Imgur

		// Instagram

		// Mobypicture

		// Polldaddy

		// Qik

		// Revision3
		$this->assertTrue( $this->Multiple->canEmbed( 'http://revision3.com/coucou/tu-veux-voir-ma-b' ));

		// Scribd
		$this->assertTrue( $this->Multiple->canEmbed( 'http://fr.scribd.com/doc/125272029/Coder-proprement' ));

		// Shoudio

		// Sketchfab

		// Slideshare

		// SoundCloud
		$this->assertTrue( $this->Multiple->canEmbed( 'https://soundcloud.com/math-electro-des-champs-1/math-electro-des-champs-super' ));

		// Twitter
		$this->assertTrue( $this->Multiple->canEmbed( 'https://twitter.com/Ouiche_Lorraine/status/278534502748405760' ));

		// Vhx

		// Yfrog

	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		// 23hq

		// Cacoo

		// Chirb.it

		// Clikthrough

		// CollegeHumour

		// Deviantart

		// Dipity

		// Flickr
		$this->assertFalse( $this->Multiple->canEmbed( 'http://www.flickr.com/tour' ));

		// Funnyordie

		// Huffduffer

		// Hulu
		$this->assertFalse( $this->Multiple->canEmbed( 'http://www.hulu.com/movies/trailers' ));

		// Ifixit

		// Imgur

		// Instagram

		// Mobypicture

		// Polldaddy

		// Qik

		// Revision3
		$this->assertFalse( $this->Multiple->canEmbed( 'http://revision3.com/networks' ));

		// Scribd
		$this->assertFalse( $this->Multiple->canEmbed( 'http://fr.scribd.com/r/art-design' ));

		// Shoudio

		// Sketchfab

		// Slideshare

		// SoundCloud
		$this->assertFalse( $this->Multiple->canEmbed( 'http://soundcloud.com/explore' ));

		// Twitter
		$this->assertFalse( $this->Multiple->canEmbed( 'https://twitter.com/i/discover' ));

		// Vhx

		// Yfrog

	}
}
