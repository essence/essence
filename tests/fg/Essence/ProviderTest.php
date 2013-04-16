<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

if ( !defined( 'ESSENCE_BOOTSTRAPPED' )) {
	require_once dirname( dirname( dirname( __FILE__ )))
		. DIRECTORY_SEPARATOR . 'bootstrap.php';
}



/**
 *
 */

class ConcreteProvider extends Provider {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#[a-z]+#';



	/**
	 *
	 */

	protected function _embed( $url, $options ) {

		return new Media( array( 'title' => 'Title' ));
	}
}



/**
 *	Test case for Provider.
 */

class ProviderTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Provider = null;



	/**
	 *
	 */

	public function setup( ) {

		$this->Provider = new ConcreteProvider( );
	}



	/**
	 *
	 */

	public function testCanEmbed( ) {

		$this->assertTrue( $this->Provider->canEmbed( 'abc' ));
	}



	/**
	 *
	 */

	public function testCantEmbed( ) {

		$this->assertFalse( $this->Provider->canEmbed( '123' ));
	}



	/**
	 *
	 */

	public function testEmbed( ) {

		$this->assertEquals(
			new Media(
				array(
					'title' => 'Title',
					'url' => 'http://foo.bar'
				)
			),
			$this->Provider->embed( '  http://foo.bar  ' )
		);
	}
}
