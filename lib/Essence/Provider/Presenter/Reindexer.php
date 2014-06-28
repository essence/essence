<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\Presenter;

use Essence\Media;
use Essence\Utility\Hash;



/**
 *	Reindexes media properties.
 */

class Reindexer {

	/**
	 *	Keys mapping.
	 *
	 *	@var array
	 */

	protected $_mapping = [ ];



	/**
	 *	Constructor.
	 *
	 *	@param array $mapping Mapping.
	 */

	public function __construct( array $mapping ) {

		$this->_mapping = $mapping;
	}



	/**
	 *	{@inheritDoc}
	 */

	public function filter( Media $Media ) {

		$Media->setProperties(
			Hash::reindex(
				$Media->properties( ),
				$this->_mapping
			)
		);

		return $Media;
	}
}
