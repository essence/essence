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

class TestableOEmbed extends OEmbed {

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

		$this->OEmbed = new TestableOEmbed(
			array(
				'endpoint' => 'file://' . ESSENCE_HTTP . '%s.json',
				'format' => OEmbed::json
			)
		);
	}



	/**
	 *
	 */

	public function testPrepare( ) {

		$this->assertEquals( 'valid', OEmbed::prepare( 'valid#anchor' ));
		$this->assertEquals( 'valid', OEmbed::prepare( 'valid?argument=value' ));
		$this->assertEquals( 'valid', OEmbed::prepare( 'valid?argument=value#anchor' ));
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

		$this->OEmbed->embed( 'invalid' );
	}



	/**
	 *
	 */

	public function testEmbedXml( ) {

		$OEmbed = new TestableOEmbed(
			array(
				'endpoint' => 'file://' . ESSENCE_HTTP . '%s.xml',
				'format' => OEmbed::xml
			)
		);

		$this->assertNotNull( $OEmbed->embed( 'valid' ));
	}



	/**
	 *
	 */

	public function testEmbedInvalidXml( ) {

		$OEmbed = new TestableOEmbed(
			array(
				'endpoint' => 'file://' . ESSENCE_HTTP . '%s.xml',
				'format' => OEmbed::xml
			)
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

		$OEmbed = new TestableOEmbed(
			array(
				'endpoint' => 'file://' . ESSENCE_HTTP . '%s.json',
				'format' => 'unsupported'
			)
		);

		try {
			$OEmbed->embed( 'valid' );
		} catch ( \Exception $e ) {
			return;
		}

		$this->fail( 'An expected exception has not been raised.' );
	}
}
