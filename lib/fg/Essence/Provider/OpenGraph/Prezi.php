<?php

/**
 *	@author Laughingwithu <laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;



/**
 *	Prezi provider (http://www.prezi.com).
 *
 *	@package fg.Essence.Provider.OpenGraph
 */

class Prezi extends \fg\Essence\Provider\OpenGraph {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#prezi\.com/.+/.+#i';

}
