<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Presenter;

use Essence\Provider\Presenter;
use Essence\Media;



/**
 *	Completes media properties.
 */
class Completer extends Presenter {

	/**
	 *	Defaults.
	 *
	 *	@var array
	 */
	protected $_defaults = [];



	/**
	 *	Constructor.
	 *
	 *	@param array $defaults Default values.
	 */
	public function __construct(array $defaults) {
		$this->_defaults = $defaults;
	}



	/**
	 *	{@inheritDoc}
	 */
	public function present(Media $Media) {
		return $Media->setDefaults($this->_defaults);
	}
}
