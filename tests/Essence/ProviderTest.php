<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use PHPUnit_Framework_TestCase;
use Essence\Log\Logger\Null as NullLogger;



/**
 *	Test case for Provider.
 */

class ProviderTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Provider = null;
	public $Media = null;



	/**
	 *
	 */

	public function setup( ) {

		$this->Media = new Media( [
			'url' => 'http://foo.bar.com/resource',
			'title' => 'Title',
			'description' => 'Description',
			'width' => 800,
			'height' => 600
		]);

		$this->Provider = $this->getMockForAbstractClass(
			'\\Essence\\Provider',
			[ new NullLogger( )]
		);
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$this->Provider
			->expects( $this->any( ))
			->method( '_embed' )
			->will( $this->returnValue( $this->Media ));

		Provider::completeMedia( $this->Media );

		$this->assertEquals(
			$this->Media,
			$this->Provider->embed( '  http://foo.bar  ' )
		);
	}



	/**
	 *
	 */

	public function testCompletePhoto( ) {

		$this->Media->set( 'type', 'photo' );
		Provider::completeMedia( $this->Media );

		$this->assertEquals(
			'<img src="http://foo.bar.com/resource" alt="Description" width="800" height="600" />',
			$this->Media->html
		);
	}



	/**
	 *
	 */

	public function testCompleteVideo( ) {

		$this->Media->set( 'type', 'video' );
		Provider::completeMedia( $this->Media );

		$this->assertEquals(
			'<iframe src="http://foo.bar.com/resource" width="800" height="600" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen />',
			$this->Media->html
		);
	}



	/**
	 *
	 */

	public function testCompleteDefault( ) {

		Provider::completeMedia( $this->Media );

		$this->assertEquals(
			'<a href="http://foo.bar.com/resource" alt="Description">Title</a>',
			$this->Media->html
		);
	}
}
