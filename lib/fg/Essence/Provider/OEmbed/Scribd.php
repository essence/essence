<?php

/**
 *	@author Laughingwithu <Laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Scribd provider (http://www.scribd.com/).
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Scribd extends \fg\Essence\Provider\OEmbed {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#scribd\.com/doc/[0-9]+/.+#i';



	/**
	 *	{@inheritDoc}
	 */

	protected $_endpoint = 'http://www.scribd.com/services/oembed?format=json&url=%s';

}
