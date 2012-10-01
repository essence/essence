<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;



/**
 *	Canal+ provider (http://www.canalplus.fr).
 *
 *	@package fg.Essence.Provider.OpenGraph
 */

class CanalPlus extends \fg\Essence\Provider\OpenGraph {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct( '#canalplus\.fr#i' );
	}
}
