<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Provider\OpenGraph;



/**
 *	TED Provider (http://www.ted.com).
 *
 *	@package Essence.Provider.OpenGraph
 */

class Ted extends \Essence\Provider\OpenGraph
{
	/**
	 *	Constructor.
	 */

	public function __construct( )
	{
		parent::__construct( '#ted\.com#i' );
	}

}
