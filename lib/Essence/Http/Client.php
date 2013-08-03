<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Http;



/**
 *	Handles HTTP related operations.
 *
 *	@package fg.Essence.Http
 */

interface Client {

	/**
	 *	Retrieves contents from the given URL.
	 *
	 *	@param string $url The URL fo fetch contents from.
	 *	@return string The contents.
	 *	@throws Essence\Http\Exception
	 */

	public function get( $url );

}
