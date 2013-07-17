<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Http;

use Essence\Exception as EssenceException;



/**
 *	An HTTP related exception.
 *
 *	@package fg.Essence.Http
 */

class Exception extends EssenceException {

	/**
	 *	HTTP status code.
	 *
	 *	@var integer
	 */

	protected $_status = 0;



	/**
	 *	Error URL.
	 *
	 *	@var string
	 */

	protected $_url = '';



	/**
	 *	Messages corresponding to HTTP codes.
	 *	Thanks to Hinnerk Brügmann (http://www.multiasking.com/2011/05/http-error-codes-as-php-array/).
	 *
	 *	@var array
	 */

	protected $_messages = array(

		// Client errors
		400 => 'Bad Request - The server did not understand the request',
		401 => 'Unauthorized - The requested page needs a username and a password',
		402 => 'Payment Required - You can not use this code yet',
		403 => 'Forbidden - Access is forbidden to the requested page',
		404 => 'Not Found - The server can not find the requested page',
		405 => 'Method Not Allowed - The method specified in the request is not allowed',
		406 => ' Not Acceptable - The server can only generate a response that is not accepted by the client',
		407 => 'Proxy Authentication Required - You must authenticate with a proxy server before this request can be served',
		408 => 'Request Timeout - The request took longer than the server was prepared to wait',
		409 => 'Conflict - The request could not be completed because of a conflict',
		410 => 'Gone - The requested page is no longer available',
		411 => 'Length Required - The "Content-Length" is not defined. The server will not accept the request without it',
		412 => 'Precondition Failed - The precondition given in the request evaluated to false by the server',
		413 => 'Request Entity Too Large - The server will not accept the request, because the request entity is too large',
		414 => 'Request-url Too Long - The server will not accept the request, because the url is too long. Occurs when you convert a "post" request to a "get" request with a long query information',
		415 => 'Unsupported Media Type - The server will not accept the request, because the media type is not supported',
		416 => 'Requested Range not satisfiable',
		417 => 'Expectation Failed',

		// Server errors
		500 => 'Internal Server Error - The request was not completed. The server met an unexpected condition',
		501 => 'Not Implemented - The request was not completed. The server did not support the functionality required',
		502 => 'Bad Gateway - The request was not completed. The server received an invalid response from the upstream server',
		503 => 'Service Unavailable - The request was not completed. The server is temporarily overloading or down',
		504 => 'Gateway Timeout - The gateway has timed out',
		505 => 'HTTP Version Not Supported - The server does not support the "http protocol" version'
	);



	/**
	 *	Constructs the exception with the given HTTP status code, and the URL
	 *	that triggered the error.
	 *
	 *	@param int $status HTTP status code.
	 *	@param string $url URL.
	 *	@param int $code Exception code.
	 *	@param Exception $Previous Previous exception.
	 */

	public function __construct( $status, $url, $code = 0, Exception $Previous = null ) {

		$this->_status = $status;
		$this->_url = $url;

		$message = isset( $this->_messages[ $status ])
			? $this->_messages[ $status ]
			: 'HTTP error';

		parent::__construct( $message, $code, $Previous );
	}



	/**
	 *	Returns the Http status code.
	 *
	 *	@return int HTTP status code.
	 */

	public function status( ) {

		return $this->_status;
	}



	/**
	 *	Returns the URL that triggered the error.
	 *
	 *	@return string URL.
	 */

	public function url( ) {

		return $this->_url;
	}
}
