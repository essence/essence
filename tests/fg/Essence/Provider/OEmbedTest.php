<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider;

if ( !defined( 'ESSENCE_BOOTSTRAPPED')) {
	require_once dirname( dirname( dirname( dirname( __FILE__ ))))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *
 */

class ConcreteOEmbed extends OEmbed {

	/**
	 *
	 */

	public function completeEndpoint( $endpoint, $options ) {

		$this->_options = $options;
		return $this->_completeEndpoint( $endpoint );
	}
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
			'file://' . ESSENCE_HTTP . '%s.json',
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

	public function testCompleteEndpoint( ) {

		$OEmbed = new ConcreteOEmbed( '', '', '' );

		$this->assertEquals(
			'url?maxwidth=120&maxheight=60',
			$OEmbed->completeEndpoint(
				'url',
				array(
					'maxwidth' => 120,
					'maxheight' => 60
				)
			)
		);

		$this->assertEquals(
			'url?maxwidth=120',
			$OEmbed->completeEndpoint(
				'url',
				array(
					'maxwidth' => 120,
					'unsupported' => 'unsupported'
				)
			)
		);

		$this->assertEquals(
			'url?param=value&maxwidth=120',
			$OEmbed->completeEndpoint(
				'url?param=value',
				array( 'maxwidth' => 120 )
			)
		);
	}



	/**
	 *
	 */

	public function testEmbedJson( ) {

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_HTTP . '%s.json',
			OEmbed::json
		);

		$this->assertNotNull( $OEmbed->embed( 'valid' ));
	}



	/**
	 *
	 */

	public function testEmbedInvalidJson( ) {

		$this->setExpectedException( '\\fg\\Essence\\Exception' );

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_HTTP . '%s.json',
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
			'file://' . ESSENCE_HTTP . '%s.xml',
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
			'file://' . ESSENCE_HTTP . '%s.xml',
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

		$this->setExpectedException( '\\fg\\Essence\\Exception' );

		$OEmbed = new ConcreteOEmbed(
			OEmbed::anything,
			'file://' . ESSENCE_HTTP . '%s.json',
			'unsupported'
		);

		$OEmbed->embed( 'valid' );
	}
}
