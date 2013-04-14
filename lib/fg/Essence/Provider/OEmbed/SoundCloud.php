<?php

/**
 *	@author Romans Malinovskis <romans@agiletoolkit.org>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	SoundCloud provider (http://developers.soundcloud.com/docs/oembed)
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class SoundCloud extends \fg\Essence\Provider\OEmbed {

	/**
	 *	{@inheritDoc}
	 */

	protected $_pattern = '#soundcloud\.com/[a-zA-Z0-9-]+/[a-zA-Z0-9-]+#i';



	/**
	 *	{@inheritDoc}
	 */

	protected $_endpoint = 'http://soundcloud.com/oembed?format=json&url=%s';

}
