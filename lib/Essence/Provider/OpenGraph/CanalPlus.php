<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence\Provider\OpenGraph;



/**
 *
 */

class CanalPlus extends \Essence\Provider\OpenGraph {

	/**
	 *
	 */

	public function __construct( ) {

		parent::__construct( '#canalplus\.fr.*vid=[0-9]+#i' );
	}
}
