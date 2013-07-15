<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;



/**
 *	Test case for OpenGraph.
 */

class OpenGraphTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $OpenGraph = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->OpenGraph = new OpenGraph( );
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$this->assertNotNull(
			$this->OpenGraph->embed( 'file://' . ESSENCE_HTTP . 'valid.html' )
		);
	}



	/**
	 *	@todo fix it for travis
	 */
	/*
	public function testEmbedInvalid( ) {

		$this->setExpectedException( '\\Essence\\Exception' );

		$this->OpenGraph->embed( 'file://' . ESSENCE_HTTP . 'invalid.html' );
	}
	*/
}
