<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OpenGraph;



/**
 *	Generic OpenGraph provider.
 *
 *	@package Essence.Provider.OpenGraph
 */

class Generic extends \Essence\Provider\OpenGraph {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct( \Essence\Provider::anything );
	}
}
