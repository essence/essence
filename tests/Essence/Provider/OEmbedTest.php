<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( __FILE__ ))) . DIRECTORY_SEPARATOR . 'bootstrap.php';
}

define(
	'OEMBED_RESPONSES',
	ESSENCE_TEST_ROOT . 'Resource' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR
);



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
			'file://' . OEMBED_RESPONSES . '%s.json',
			OEmbed::json
		);

		$Embed = $OEmbed->fetch( 'valid#anchor' );
		$this->assertEquals( 'valid', $Embed->get( 'url' ));

		$Embed = $OEmbed->fetch( 'valid?argument=value' );
		$this->assertEquals( 'valid', $Embed->get( 'url' ));

		$Embed = $OEmbed->fetch( 'valid?argument=value#anchor' );
		$this->assertEquals( 'valid', $Embed->get( 'url' ));
	}



	/**
	 *
	 */

	public function testFetchJson( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . OEMBED_RESPONSES . '%s.json',
			OEmbed::json
		);

		$this->assertNotNull( $OEmbed->fetch( 'valid' ));
	}



	/**
	 *
	 */

	public function testFetchInvalidJson( ) {

		$this->setExpectedException( '\\Essence\\Exception' );

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . OEMBED_RESPONSES . '%s.json',
			OEmbed::json
		);

		$OEmbed->fetch( 'invalid' );
	}



	/**
	 *
	 */

	public function testFetchXml( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . OEMBED_RESPONSES . '%s.xml',
			OEmbed::xml
		);

		$this->assertNotNull( $OEmbed->fetch( 'valid' ));
	}



	/**
	 *
	 */

	public function testFetchInvalidXml( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . OEMBED_RESPONSES . '%s.xml',
			OEmbed::xml
		);

		try {
			$OEmbed->fetch( 'invalid' );
		} catch ( \Exception $e ) {
			return;
		}

		$this->fail( 'An expected exception has not been raised.' );
	}



	/**
	 *
	 */

	public function testFetchUnsupportedFormat( ) {

		$this->setExpectedException( '\\Essence\\Exception' );

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . OEMBED_RESPONSES . '%s.json',
			'unsupported'
		);

		$OEmbed->fetch( 'valid' );
	}
}



/**
 *
 */

class ConcreteOEmbed extends \Essence\Provider\OEmbed {

}