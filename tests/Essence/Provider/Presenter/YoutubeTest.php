<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Presenter;

use PHPUnit_Framework_TestCase as TestCase;
use Essence\Media;



/**
 *	Test case for Youtube.
 */
class YoutubeTest extends TestCase {

	/**
	 *
	 */
	public $Media = null;



	/**
	 *
	 */
	public function setup() {
		$this->Media = new Media([
			'thumbnailUrl' => 'http://i1.ytimg.com/vi/5jmjBXoyugM/hqdefault.jpg'
		]);
	}



	/**
	 *
	 */
	public function testPresentSmall() {
		$Youtube = new Youtube(Youtube::small);

		$this->assertEquals(
			'http://i1.ytimg.com/vi/5jmjBXoyugM/default.jpg',
			$Youtube->present($this->Media)->get('thumbnailUrl')
		);
	}



	/**
	 *
	 */
	public function testPresentMedium() {
		$Youtube = new Youtube(Youtube::medium);

		$this->assertEquals(
			'http://i1.ytimg.com/vi/5jmjBXoyugM/mqdefault.jpg',
			$Youtube->present($this->Media)->get('thumbnailUrl')
		);
	}



	/**
	 *
	 */
	public function testPresentLarge() {
		$Youtube = new Youtube(Youtube::large);

		$this->assertEquals(
			'http://i1.ytimg.com/vi/5jmjBXoyugM/hqdefault.jpg',
			$Youtube->present($this->Media)->get('thumbnailUrl')
		);
	}
}
