<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Exception as NativeException;



/**
 *	The base exception class of the Essence API.
 */
class Exception extends NativeException {

	/**
	 *	Wraps a native PHP exception.
	 *
	 *	@param NativeException Native exception.
	 *	@return Exception Essence exception.
	 */
	public static function wrap(NativeException $Exception) {
		return new Exception(
			$Exception->getMessage(),
			$Exception->getCode(),
			$Exception
		);
	}
}
