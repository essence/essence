<?php

/**
 *	@author Laughingwithu <Laughingwithu@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Hulu provider (http://www.hulu.com/).
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Hulu extends \fg\Essence\Provider\OEmbed {

	/**
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct(
			'#hulu\\.com/watch/.*+#i',
			'http://www.hulu.com/api/oembed.json',
			self::json
		);
	}
}
