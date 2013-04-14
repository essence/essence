<?php

/**
 *	@author Laughingwithu <Laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	revision3 provider (http://www.revision3.com/).
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Revision3 extends \fg\Essence\Provider\OEmbed {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#revision3\.com/[a-z0-9]/.+#i';



	/**
	 *	{@inheritDoc}
	 */

	protected $_endpoint = 'http://revision3.com/api/oembed?format=json&url=%s';

}
