<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Dom\Parser;

use PHPUnit_Framework_TestCase;



/**
 *	Test case for Native.
 */

class NativeTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 */

	public $Native = null;



	/**
	 *
	 */

	public $html = <<<'HTML'
		<meta name="description" content="Description." />
		<meta name="ns:custom" content="Custom namespace." />

		<a href="http://www.test.com" title="Link">
		<a href="http://www.othertest.com" title="Other link" target="_blank">
HTML;



	/**
	 *
	 */

	public function setUp( ) {

		$this->Native = new Native( );
	}



	/**
	 *
	 */

	public function testExtractAttributes( ) {

		$this->setExpectedException( '\\Essence\\Exception' );
		$this->Native->extractAttributes( '', array( ));
	}



	/**
	 *
	 */

	public function testExtractAttributesFromUnknownTag( ) {

		$this->assertEquals(
			array(
				'unknown' => array( )
			),
			$this->Native->extractAttributes( $this->html, array( 'unknown' ))
		);
	}



	/**
	 *
	 */

	public function testExtractAllAttributesFromTag( ) {

		$this->assertEquals(
			array(
				'a' => array(
					array(
						'href' => 'http://www.test.com',
						'title' => 'Link'
					),
					array(
						'href' => 'http://www.othertest.com',
						'title' => 'Other link',
						'target' => '_blank'
					)
				)
			),
			$this->Native->extractAttributes( $this->html, array( 'a' ))
		);
	}



	/**
	 *
	 */

	public function testExtractSomeAttributesFromTag( ) {

		$this->assertEquals(
			array(
				'a' => array(
					array(
						'href' => 'http://www.othertest.com',
						'target' => '_blank'
					)
				)
			),
			$this->Native->extractAttributes(
				$this->html,
				array(
					'a' => array( 'href', 'target' )
				)
			)
		);
	}



	/**
	 *
	 */

	public function testExtractFilteredAttributesFromTag( ) {

		$this->assertEquals(
			array(
				'meta' => array(
					array(
						'name' => 'ns:custom',
						'content' => 'Custom namespace.'
					)
				)
			),
			$this->Native->extractAttributes(
				$this->html,
				array(
					'meta' => array( 'name' => '#^ns:.+#', 'content' )
				)
			)
		);
	}



	/**
	 *
	 */

	public function testExtractAllAttributesFromMultipleTags( ) {

		$this->assertEquals(
			array(
				'meta' => array(
					array(
						'name' => 'description',
						'content' => 'Description.'
					),
					array(
						'name' => 'ns:custom',
						'content' => 'Custom namespace.'
					)
				),
				'a' => array(
					array(
						'href' => 'http://www.test.com',
						'title' => 'Link'
					),
					array(
						'href' => 'http://www.othertest.com',
						'title' => 'Other link',
						'target' => '_blank'
					)
				)
			),
			$this->Native->extractAttributes(
				$this->html,
				array( 'meta', 'a' )
			)
		);
	}
}
