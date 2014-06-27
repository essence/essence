<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Filter\Presenter;

use Essence\Media;
use Essence\Utility\Template;



/**
 *	Reindexes media properties.
 */

class Templater {

	/**
	 *	Keys mapping.
	 *
	 *	@var array
	 */

	protected $_property = [ ];



	/**
	 *	Keys mapping.
	 *
	 *	@var array
	 */

	protected $_switch = [ ];



	/**
	 *	Keys mapping.
	 *
	 *	@var array
	 */

	protected $_templates = [ ];



	/**
	 *	Constructor.
	 *
	 *	@param array $mapping Mapping.
	 */

	public function __construct( $property, $switch, array $templates ) {

		$this->_property = $property;
		$this->_switch = $switch;
		$this->_templates = $templates;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function filter( Media $Media ) {

		$switch = $Media->get( $this->_switch );

		if ( $switch && !$Media->has( $this->_property )) {
			foreach ( $this->_templates as $pattern => $template ) {
				if ( preg_match( $pattern, $switch, $matches )) {
					$Media->set( $this->_property, Template::compile(
						$template,
						$Media->properties( ),
						'htmlspecialchars'
					));

					break;
				}
			}
		}

		return $Media;
	}
}
