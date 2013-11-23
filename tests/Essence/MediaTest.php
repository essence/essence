<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

use PHPUnit_Framework_TestCase;



/**
 *	Test case for Media.
 */

class MediaTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $properties = array(
		'url' => 'http://foo.bar.com/resource',
		'title' => 'Title',
		'description' => 'Description',
		'width' => 800,
		'height' => 600,
		'custom' => 'Custom property'
	);



	/**
	 *
	 */

	public $Media = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Media = new Media( $this->properties );
	}



	/**
	 *
	 */

	public function testConstruct( ) {

		$this->assertTrue( $this->Media->has( 'custom' ));
	}



	/**
	 *
	 */

	public function testIterator( ) {

		foreach ( $this->Media as $property => $value ) { }
	}



	/**
	 *
	 */

	public function testSerialize( ) {

		$this->assertEquals(
			json_encode( $this->Media->properties( )),
			json_encode( $this->Media )
		);
	}



	/**
	 *
	 */

	public function testCompletePhoto( ) {

		$this->Media->set( 'type', 'photo' );
		$this->Media->complete( );

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
		$this->Media->complete( );

		$this->assertEquals(
			'<iframe src="http://foo.bar.com/resource" width="800" height="600" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen />',
			$this->Media->html
		);
	}



	/**
	 *
	 */

	public function testCompleteDefault( ) {

		$this->Media->complete( );

		$this->assertEquals(
			'<a href="http://foo.bar.com/resource" alt="Description">Title</a>',
			$this->Media->html
		);
	}
}
