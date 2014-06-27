<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Utility;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Template.
 */

class TemplateTest extends TestCase {

	/**
	 *
	 */

	public function testCompile( ) {

		$template = Template::compile( ':foo {:bar}', [
			'foo' => 'text',
			'bar' => '<html>'
		]);

		$this->assertEquals( 'text &lt;html&gt;', $template );
	}
}
