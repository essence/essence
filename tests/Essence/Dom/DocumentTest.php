<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Dom;

use PHPUnit_Framework_TestCase as TestCase;
use ReflectionClass;



/**
 *	Test case for Document.
 */
class DocumentTest extends TestCase {

	/**
	 *
	 */
	public function testConstruct() {
		$Document = $this->getMockForAbstractClass('Essence\Dom\Document', [
			'html'
		]);

		$Reflection = new ReflectionClass($Document);
		$Property = $Reflection->getProperty('_html');
		$Property->setAccessible(true);

		$this->assertEquals('html', $Property->getValue($Document));
	}
}
