<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Presenter;

use Essence\Provider\Presenter;
use Essence\Media;



/**
 *
 */
class Youtube extends Presenter {

	/**
	 *	Available thumbnail formats.
	 */
	const small = 'small';
	const medium = 'medium';
	const large = 'large';
	const max = 'max';



	/**
	 *	Replacements for the thumbnail file name.
	 *
	 *	@var array
	 */
	protected $_names = [
		self::small => 'default',
		self::medium => 'mqdefault',
		self::max => 'maxresdefault'
	];



	/**
	 *	Thumbnail format.
	 *
	 *	@var string
	 */
	protected $_format = '';



	/**
	 *	Property in which the thumbnail URL is stored.
	 *
	 *	@var string
	 */
	protected $_property = '';



	/**
	 *	Constructor.
	 *
	 *	@param string $format Thumbnail format.
	 *	@param string $property Thumbnail property.
	 */
	public function __construct($format, $property = 'thumbnailUrl') {
		$this->_format = $format;
		$this->_property = $property;
	}



	/**
	 *	{@inheritDoc}
	 */
	public function present(Media $Media) {
		$url = $Media->get($this->_property);

		if ($url && isset($this->_names[$this->_format])) {
			$thumbnail = str_replace(
				'hqdefault',
				$this->_names[$this->_format],
				$url
			);

			$Media->set($this->_property, $thumbnail);
		}

		return $Media;
	}
}
