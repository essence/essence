<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( __FILE__ ))) . DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *
 */

class ConcreteOEmbed extends \Essence\Provider\OEmbed {

}



/**
 *	Test case for OEmbed.
 */

class OEmbedTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testFetch( ) {

		$OEmbed = new ConcreteOEmbed( \Essence\Provider::anything, 'json?url=%s', 'json' );
	}
}