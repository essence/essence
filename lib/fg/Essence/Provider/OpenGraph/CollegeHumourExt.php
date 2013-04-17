<?php

/**
 *	@author Laughingwithu <Laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OpenGraph;



/**
 *	College Humour Images and Articles provider (http://www.collegehumour.com).
 *
 *	@package fg.Essence.Provider.OpenGraph
 */

class CollegeHumourExt extends \fg\Essence\Provider\OpenGraph {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#collegehumor.com/(picture|article)/.+#i';

}
