<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use PHPUnit_Framework_TestCase;



/**
 *	Test case for Provider.
 */

class ProviderTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Provider = null;



	/**
	 *
	 */

	public function setup( ) {

		$Media = new Media( array( 'title' => 'Title' ));

		$this->Provider = $this->getMockForAbstractClass( '\\Essence\\Provider' );
		$this->Provider
			->expects( $this->any( ))
			->method( '_embed' )
			->will( $this->returnValue( $Media ));
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$this->Provider->set( 'complete', false );

		$this->assertEquals(
			new Media( array(
				'title' => 'Title',
				'url' => 'http://foo.bar'
			)),
			$this->Provider->embed( '  http://foo.bar  ' )
		);
	}



	/**
	 *
	 */

	public function testCompletePhoto( ) {

		$Media = new Media( array(
			'type' => 'photo',
			'url' => 'http://static.foo.com/photos/123456.jpg',
			'title' => 'Title',
			'width' => 1024,
			'height' => 768
		));

		$this->assertEquals(
			'<img src="http://static.foo.com/photos/123456.jpg" alt="Title" width="1024" height="768" />',
			Provider::completeMedia( $Media )->html
		);
	}



	/**
	 *
	 */

	public function testCompleteDefault( ) {

		$Media = new Media( array(
			'url' => 'http://www.youtube.com/watch?v=123456',
			'title' => 'Title',
			'description' => 'A video'
		));

		$this->assertEquals(
			'<a href="http://www.youtube.com/watch?v=123456" alt="A video">Title</a>',
			Provider::completeMedia( $Media )->html
		);
	}
}
