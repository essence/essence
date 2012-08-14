<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



/**
 *	Handles HTTP related operations.
 *
 *	@package Essence
 */

class Http {

	/**
	 *	Returns the contents of an URL.
	 *
	 *	@param string $url The URL fo fetch content from.
	 *	@return string|false The read contents, or false if anything went wrong.
	 */
	
	public static function get( $url ) {

		$html = @file_get_contents( $url );

		if ( $html === false ) {
			throw new HttpException( 404 );
		}

		return $html;
	}
}



/**
 *	An HTTP related exception.
 *
 *	@package Essence
 */

class HttpException extends Exception {

	/**
	 *	HTTP code.
	 *
	 *	@var int
	 */

	protected $_code = 0;



	/**
	 *	Messages corresponding to HTTP codes.
	 *
	 *	@var array
	 */

	protected $_messages = array(
		404 => 'Not Found'
	);



	/**
	 *	Constructs the exception with the given HTTP code.
	 *
	 *	@param int $code HTTP code.
	 */

	public function __construct( $code ) {

		$message = isset( $this->_messages[ $code ])
			? $this->_messages[ $code ]
			: '';

		parent::__construct( $message );
	}
}
