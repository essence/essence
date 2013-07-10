<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Utility;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( dirname( __FILE__ )))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *	Test case for Autoload.
 */

class AutoloadTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testSetup( ) {

		$this->assertTrue( class_exists( '\\Essence\\Provider\\OEmbed' ));
	}
}
