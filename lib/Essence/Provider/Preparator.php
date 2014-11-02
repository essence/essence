<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;



/**
 *	Prepares URLs before extraction.
 */
abstract class Preparator {

	/**
	 *	@see prepare()
	 */
	public function __invoke($url) {
		return $this->prepare($url);
	}



	/**
	 *	Prepares the given URL.
	 *
	 *	@param string $url URL.
	 *	@return string Prepared URL.
	 */
	abstract public function prepare($url);

}
