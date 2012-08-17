<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OEmbed;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once
		dirname( dirname( dirname( dirname( __FILE__ ))))
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

		$this->Dailymotion = new Dailymotion( );

		$Reflection = new \ReflectionClass( '\\Essence\\Provider\\OEmbed\\Dailymotion' );

		$Property = $Reflection->getProperty( '_endpoint' );
		$Property->setAccessible( true );
		$Property->setValue( $this->Dailymotion, 'file://' . ESSENCE_TEST_HTTP . '%s.json' );
	}



	/**
	 *
	 */

	public function testCanFetch( ) {

		$this->assertTrue( $this->Dailymotion->canFetch( 'http://www.dailymotion.com/video/xlyy09_very-bad-blagues-le-premier-p-tit-dej_fun' ));
	}



	/**
	 *
	 */

	public function testCantFetch( ) {

		$this->assertFalse( $this->Dailymotion->canFetch( 'http://www.youtube.com/watch?v=HgKXN_Uw2ME' ));
	}



	/**
	 *
	 */

	public function testFetch( ) {

		$Embed = $this->Dailymotion->fetch( 'http://www.dailymotion.com/video/x51w7_peter-et-steven_fun' );

		$this->assertEquals(
			'http://static2.dmcdn.net/static/video/537/532/235735:jpeg_preview_source.jpg?20110928233928',
			$Embed->thumbnailUrl
		);
	}
}
