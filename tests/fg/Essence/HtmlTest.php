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
 *	Test case for Html.
 */

class HtmlTest extends \PHPUnit_Framework_TestCase {

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

	public function testExtractAttributes( ) {

		$this->setExpectedException( '\\fg\\Essence\\Exception' );

		Html::extractAttributes( '', array( ));
	}



	/**
	 *
	 */

	public function testExtractAttributesFromUnknownTag( ) {

		$this->assertEquals(
			array(
				'unknown' => array( )
			),
			Html::extractAttributes( $this->html, array( 'unknown' ))
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
			Html::extractAttributes( $this->html, array( 'a' ))
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
			Html::extractAttributes(
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
			Html::extractAttributes(
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
			Html::extractAttributes( $this->html, array( 'meta', 'a' ))
		);
	}
}
