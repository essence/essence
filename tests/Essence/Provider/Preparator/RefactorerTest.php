<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Preparator;

use PHPUnit_Framework_TestCase as TestCase;



/**
 *	Test case for Refactorer.
 */
class RefactorerTest extends TestCase {

	/**
	 *
	 */
	public $Refactorer = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Refactorer = new Refactorer(
			'#player\.vimeo\.com/video/(?<id>[0-9]+)#i',
			'http://www.vimeo.com/:id'
		);
	}



	/**
	 *
	 */
	public function testPrepare() {
		$this->assertEquals(
			'http://www.vimeo.com/123456',
			$this->Refactorer->prepare('http://player.vimeo.com/video/123456')
		);
	}
}
