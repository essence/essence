<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Utility;

use PHPUnit_Framework_TestCase;



/**
 *	Test case for Xml.
 */

class XmlTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $valid = <<<VALID
<?xml version="1.0" encoding="utf-8"?>

		<oembed>
			<title>Title</title>
			<type>video</type>
		</oembed>

VALID;



	/**
	 *
	 */

	public $invalid = <<<VALID
		<oembed>
			<title>Title
			<type>video</type>
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
			Xml::parse( $this->valid )
		);
	}



	/**
	 *
	 */

	public function testParseInvalid( ) {

		$this->setExpectedException( '\\Exception' );

		Xml::parse( $this->invalid );
	}
}
