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
 *	Test case for OpenGraph.
 */

class DailymotionTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testCanFetch( ) {

		$Dailymotion = new Dailymotion( );

		$this->assertTrue( $Dailymotion->canFetch( 'http://www.dailymotion.com/video/xlyy09_very-bad-blagues-le-premier-p-tit-dej_fun' ));
	}



	/**
	 *
	 */

	public function testCantFetch( ) {

		$Dailymotion = new Dailymotion( );

		$this->assertFalse( $Dailymotion->canFetch( 'http://www.youtube.com/watch?v=HgKXN_Uw2ME' ));
	}



	/**
	 *
	 */

	public function testFetch( ) {

		$Reflection = new \ReflectionClass( '\\Essence\\Provider\\OEmbed\\Dailymotion' );
		$Dailymotion = new Dailymotion( );

		$property = $Reflection->getProperty( '_endpoint' );
		$property->setAccessible( true );
		$property->setValue( $Dailymotion, 'file://' . ESSENCE_TEST_HTTP . '%s.json' );

		$Embed = $Dailymotion->fetch( 'dailymotion' );

		$this->assertEquals(
			'http://static2.dmcdn.net/static/video/537/532/235735:jpeg_preview_source.jpg?20110928233928',
			$Embed->get( 'thumbnailUrl' )
		);
	}
}