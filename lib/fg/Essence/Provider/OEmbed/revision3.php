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
	 *	Constructor.
	 */

	public function __construct( ) {

		parent::__construct(
			'#revision3\.com/.+#i',
			'http://revision3.com/api/oembed?format=json&url=%s',
			self::json
		);
	}
}
