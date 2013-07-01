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
 *	Test case for Youtube.
 */

class YoutubeTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Youtube = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Youtube = new Youtube(
			array(
				'endpoint' => 'file://' . ESSENCE_HTTP . '%s.json'
			)
		);
	}



	/**
	 *
	 */

	public function testPrepare( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareOldSchool( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/v/oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareEmbed( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/embed/oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareShortened( ) {

		$Media = $this->Youtube->embed( 'http://youtu.be/oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}



	/**
	 *
	 */

	public function testPrepareAlreadyPrepared( ) {

		$Media = $this->Youtube->embed( 'http://www.youtube.com/watch?v=oHg5SJYRHA0' );

		$this->assertEquals(
			'http://www.youtube.com/watch?v=oHg5SJYRHA0',
			$Media->url
		);
	}
}
