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
 *	Test case for Dailymotion.
 */

class DailymotionTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Dailymotion = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Dailymotion = new Dailymotion(
			array(
				'endpoint' => 'file://' . ESSENCE_HTTP . '%s.json'
			)
		);
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$Media = $this->Dailymotion->embed( 'http://www.dailymotion.com/video/x51w7_peter-et-steven_fun' );

		$this->assertEquals(
			'http://static2.dmcdn.net/static/video/537/532/235735:jpeg_preview_source.jpg?20110928233928',
			$Media->thumbnailUrl
		);
	}
}
