<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( __FILE__ )) . DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Http.
 */

class HttpTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testGet( ) {

		$this->assertNotEmpty( Http::get( 'file://' . __FILE__ ));
	}



	/**
	 *
	 */

	public function testGetUnreachable( ) {

		$this->setExpectedException( '\\Essence\\HttpException' );

		Http::get( 'file://' . __FILE__ . '.unreachable' );
	}
}
