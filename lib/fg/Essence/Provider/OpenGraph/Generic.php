<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;

use fg\Essence\Provider\OpenGraph;



/**
 *	Generic OpenGraph provider.
 *
 *	@package fg.Essence.Provider.OpenGraph
 */

class Generic extends OpenGraph {

	/**
	 *	{@inheritDoc}
	 */

	protected $_generic = true;



	/**
	 *	{@inheritDoc}
	 */

	public function canEmbed( $url ) {

		return ( $this->_extractInformations( $url ) !== false );
	}
}
