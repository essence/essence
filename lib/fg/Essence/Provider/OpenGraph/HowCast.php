<?php

/**
 *	@author Laughingwithu <laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;



/**
 *	How Cast provider (http://www.howcast.com).
 *	Test URL: http://www.howcast.com/guides/1073-How-to-Shave
 *
 *	@package fg.Essence.Provider.OpenGraph
 */

class HowCast extends \fg\Essence\Provider\OpenGraph {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#howcast\.com/.+/.+#i';

}

