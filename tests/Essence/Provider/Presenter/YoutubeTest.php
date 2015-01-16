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
	 *	@dataProvider thumbnailFormatProvider
	 */
	public function testPresent($format, $file) {
		$Youtube = new Youtube($format);

		$this->assertEquals(
			"http://i1.ytimg.com/vi/5jmjBXoyugM/$file.jpg",
			$Youtube->present($this->Media)->get('thumbnailUrl')
		);
	}



	/**
	 *
	 */
	public function thumbnailFormatProvider() {
		return [
			[Youtube::small, 'default'],
			[Youtube::medium, 'mqdefault'],
			[Youtube::large, 'hqdefault'],
			[Youtube::max, 'maxresdefault']
		];
	}
}
