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
 *	Test case for OEmbed.
 */

class OEmbedTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public function testPrepare( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_TEST_HTTP . '%s.json',
			OEmbed::json
		);

		$Media = $OEmbed->embed( 'valid#anchor' );
		$this->assertEquals( 'valid', $Media->url );

		$Media = $OEmbed->embed( 'valid?argument=value' );
		$this->assertEquals( 'valid', $Media->url );

		$Media = $OEmbed->embed( 'valid?argument=value#anchor' );
		$this->assertEquals( 'valid', $Media->url );
	}



	/**
	 *
	 */

	public function testEmbedJson( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_TEST_HTTP . '%s.json',
			OEmbed::json
		);

		$this->assertNotNull( $OEmbed->embed( 'valid' ));
	}



	/**
	 *
	 */

	public function testEmbedInvalidJson( ) {

		$this->setExpectedException( '\\Essence\\Exception' );

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_TEST_HTTP . '%s.json',
			OEmbed::json
		);

		$OEmbed->embed( 'invalid' );
	}



	/**
	 *
	 */

	public function testEmbedXml( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_TEST_HTTP . '%s.xml',
			OEmbed::xml
		);

		$this->assertNotNull( $OEmbed->embed( 'valid' ));
	}



	/**
	 *
	 */

	public function testEmbedInvalidXml( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_TEST_HTTP . '%s.xml',
			OEmbed::xml
		);

		try {
			$OEmbed->embed( 'invalid' );
		} catch ( \Exception $e ) {
			return;
		}

		$this->fail( 'An expected exception has not been raised.' );
	}



	/**
	 *
	 */

	public function testEmbedUnsupportedFormat( ) {

		$this->setExpectedException( '\\Essence\\Exception' );

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_TEST_HTTP . '%s.json',
			'unsupported'
		);

		$OEmbed->embed( 'valid' );
	}
}



/**
 *
 */

class ConcreteOEmbed extends \Essence\Provider\OEmbed {

}