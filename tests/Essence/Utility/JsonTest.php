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
 *	Test case for Json.
 */

class JsonTest extends \PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $valid = <<<VALID
		{
			"title": "Title",
			"type": "video"
		}
VALID;

	/**
	 *
	 */

	public $invalid = <<<VALID
		{
			"title" "Title",
			"type": "video"
VALID;



	/**
	 *
	 */

	public function testParse( ) {

		$this->assertEquals(
			array(
				'title' => 'Title',
				'type' => 'video'
			),
			Json::parse( $this->valid )
		);
	}



	/**
	 *
	 */

	public function testParseInvalid( ) {

		$this->setExpectedException( 'Essence\\Exception' );

		Json::parse( $this->invalid );
	}
}
