<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Http;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for FileGetContents.
 */

class FileGetContentsTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $FileGetContents = null;



	/**
	 *
	 */

	public function setUp( ) {

		$this->FileGetContents = new FileGetContents( );
	}



	/**
	 *
	 */

	public function testGet( ) {

		$this->assertNotEmpty( $this->FileGetContents->get( 'file://' . __FILE__ ));
	}



	/**
	 *
	 */

	public function testGetUnreachable( ) {

		$this->setExpectedException( '\\fg\\Essence\\Http\\Exception' );
		$this->FileGetContents->get( 'file://' . __FILE__ . '.unreachable' );
	}
}
