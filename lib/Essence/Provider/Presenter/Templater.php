<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\Presenter;

use Essence\Provider\Presenter;
use Essence\Media;
use Essence\Utility\Template;



/**
 *	Reindexes media properties.
 */
class Templater extends Presenter {

	/**
	 *	Property to update.
	 *
	 *	@var string
	 */
	protected $_property = '';



	/**
	 *	Property to test.
	 *
	 *	@var string
	 */
	protected $_switch = '';



	/**
	 *	Templates.
	 *
	 *	@var array
	 */
	protected $_templates = [];



	/**
	 *	Constructor.
	 *
	 *	@param string $property Property to update.
	 *	@param string $switch Property to test.
	 *	@param array $templates Mapping.
	 */
	public function __construct($property, $switch, array $templates) {
		$this->_property = $property;
		$this->_switch = $switch;
		$this->_templates = $templates;
	}



	/**
	 *	{@inheritDoc}
	 */
	public function present(Media $Media) {
		$switch = $Media->get($this->_switch);

		if ($switch && !$Media->has($this->_property)) {
			foreach ($this->_templates as $pattern => $template) {
				if (preg_match($pattern, $switch)) {
					$Media->set($this->_property, Template::compile(
						$template,
						$Media->properties(),
						'htmlspecialchars'
					));

					break;
				}
			}
		}

		return $Media;
	}
}
