<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Twitter provider (http://twitter.com).
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Twitter extends \fg\Essence\Provider\OEmbed {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct(
			'#twitter\.com/[a-zA-Z0-9_]+/status/.+#i',
			'https://api.twitter.com/1/statuses/oembed.json?url=%s',
			self::json
		);
	}
}
