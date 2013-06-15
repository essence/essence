<?php

/**
 *	@author Laughingwithu <Laughingwithu@gmail.com>
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;

use fg\Essence\Provider\OpenGraph;



/**
 *	CollegeHumor images and articles provider (http://www.collegehumor.com).
 *
 *	@package fg.Essence.Provider.OpenGraph
 */

class CollegeHumor extends OpenGraph {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#collegehumor.com/(picture|article)/.+#i';

}
