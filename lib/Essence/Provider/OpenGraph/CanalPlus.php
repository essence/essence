<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OpenGraph;



/**
 *	Canal+ provider (http://www.canalplus.fr).
 *
 *	@package Essence.Provider.OpenGraph
 */

class CanalPlus extends \Essence\Provider\OpenGraph {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct( '#canalplus\.fr#i' );
	}
}
