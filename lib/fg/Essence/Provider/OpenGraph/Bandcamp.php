<?php

/**
 *	@author Laughingwithu <Laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;



/**
 *	Bandcamp provider (http://www.bandcamp.com).
 *
 *	@package fg.Essence.Provider.OpenGraph
 */

class Bandcamp extends \fg\Essence\Provider\OpenGraph {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#bandcamp\.com/(album|track)/#i';

}

