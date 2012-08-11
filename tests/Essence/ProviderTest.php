<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;

require_once dirname( dirname( __FILE__ )) . DIRECTORY_SEPARATOR . 'bootstrap.php';



/**
 *	Test case for Provider.
 */

class ProviderTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testCanFetch( ) {

		$Provider = new Provider( '#[a-z]+#i' );

		$this->assertTrue( $Provider->canFetch( 'abc' ));
		$this->assertFalse( $Provider->canFetch( '123' ));		
	}
}