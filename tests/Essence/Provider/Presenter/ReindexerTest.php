<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Presenter;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Media;



/**
 *	Test case for Reindexer.
 */
class ReindexerTest extends TestCase {

	/**
	 *
	 */
	public $Reindexer = null;



	/**
	 *
	 */
	public function setUp() {
		$this->Reindexer = new Reindexer([
			'old' => 'new'
		]);
	}



	/**
	 *
	 */
	public function testPresent() {
		$Media = new Media([
			'old' => 'value'
		]);

		$Media = $this->Reindexer->present($Media);
		$this->assertEquals('value', $Media->get('new'));
	}
}
