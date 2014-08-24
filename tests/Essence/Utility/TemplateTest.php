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
	public function testCompile() {
		$template = Template::compile(':foo {:bar}', [
			'foo' => 'foo',
			'bar' => 'bar'
		]);

		$this->assertEquals('foo bar', $template);

		$template = Template::compile(':html', [
			'html' => '<html>',
		], 'htmlspecialchars');

		$this->assertEquals('&lt;html&gt;', $template);
	}
}
