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
	 *	{@inheritDoc}
	 */

	public function __construct( ) {

		$this->_endpoint = 'file://' . ESSENCE_HTTP . '%s.json';
	}



	/**
	 *
	 */

	public function setEndpoint( $endpoint ) {

		$this->_endpoint = $endpoint;
	}



	/**
	 *
	 */

	public function setFormat( $format ) {

		$this->_format = $format;
	}



	/**
	 *
	 */

	public function completeEndpoint( $endpoint, $options ) {

		return $this->_completeEndpoint( $endpoint, $options );
	}
}



/**
 *	Test case for OEmbed.
 */

class OEmbedTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $OEmbed = null;



	/**
	 *
	 */

	public function setup( ) {

		$this->OEmbed = new ConcreteOEmbed( );
	}



	/**
	 *
	 */

	public function testPrepare( ) {


		$Media = $this->OEmbed->embed( 'valid#anchor' );
		$this->assertEquals( 'valid', $Media->url );

		$Media = $this->OEmbed->embed( 'valid?argument=value' );
		$this->assertEquals( 'valid', $Media->url );

		$Media = $this->OEmbed->embed( 'valid?argument=value#anchor' );
		$this->assertEquals( 'valid', $Media->url );
	}



	/**
	 *
	 */

	public function testCompleteEndpoint( ) {


		$this->assertEquals(
			'url?maxwidth=120&maxheight=60',
			$this->OEmbed->completeEndpoint(
				'url',
				array(
					'maxwidth' => 120,
					'maxheight' => 60
				)
			)
		);

		$this->assertEquals(
			'url?maxwidth=120',
			$this->OEmbed->completeEndpoint(
				'url',
				array(
					'maxwidth' => 120,
					'unsupported' => 'unsupported'
				)
			)
		);

		$this->assertEquals(
			'url?param=value&maxwidth=120',
			$this->OEmbed->completeEndpoint(
				'url?param=value',
				array( 'maxwidth' => 120 )
			)
		);
	}



	/**
	 *
	 */

	public function testEmbedJson( ) {


		$this->assertNotNull( $this->OEmbed->embed( 'valid' ));
	}



	/**
	 *
	 */

	public function testEmbedInvalidJson( ) {

		$this->setExpectedException( '\\fg\\Essence\\Exception' );

		$this->OEmbed->setEndpoint( 'file://' . ESSENCE_HTTP . '%s.xml' );
		$this->OEmbed->embed( 'invalid' );
	}



	/**
	 *
	 */

	public function testEmbedXml( ) {

		$this->OEmbed->setEndpoint( 'file://' . ESSENCE_HTTP . '%s.xml' );
		$this->OEmbed->setFormat( OEmbed::xml );

		$this->assertNotNull( $this->OEmbed->embed( 'valid' ));
	}



	/**
	 *
	 */

	public function testEmbedInvalidXml( ) {

		$this->OEmbed->setEndpoint( 'file://' . ESSENCE_HTTP . '%s.xml' );
		$this->OEmbed->setFormat( OEmbed::xml );

		try {
			$this->OEmbed->embed( 'invalid' );
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

		$this->OEmbed->setFormat( 'unsupported' );
		$this->OEmbed->embed( 'valid' );
	}
}
