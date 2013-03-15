<?php

/**
 *	@author Romans Malinovskis <romans@agiletoolkit.org>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Soundcloud provider (http://developers.soundcloud.com/docs/oembed)
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class SoundCloud extends \fg\Essence\Provider\OEmbed {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct(
			'#soundcloud\.com/[a-zA-Z0-9_]+/.+#i',
			'http://soundcloud.com/oembed.json?url=%s',
			self::json
		);
	}
}
