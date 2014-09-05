<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Http\Client;

use Essence\Http\Client;
use Essence\Http\Exception;



/**
 *	Handles HTTP related operations through file_get_contents().
 */
class Native implements Client {

	/**
	 *	Default HTTP status code.
	 *
	 *	@var int
	 */
	protected $_defaultCode;



	/**
	 *	Constructor.
	 *
	 *	@param int $defaultCode The default HTTP status code to assume if
	 *		response headers cannot be parsed.
	 */
	public function __construct($defaultCode = 404) {
		$this->_defaultCode = $defaultCode;
	}



	/**
	 *	Retrieves contents from the given URL.
	 *	Thanks to Diije for the hint on $http_response_header
	 *	(http://www.felix-girault.fr/astuces/recuperer-une-page-web-en-php/#comment-1029).
	 *
	 *	@param string $url The URL fo fetch contents from.
	 *	@return string The fetched contents.
	 *	@throws Essence\Http\Exception
	 */
	public function get($url) {
		$reporting = error_reporting(0);
		$contents = file_get_contents($url);
		error_reporting($reporting);

		if ($contents === false) {
			$code = isset($http_response_header[0])
				? $this->_extractHttpCode($http_response_header[0])
				: $this->_defaultCode;

			throw new Exception($url, $code);
		}

		return $contents;
	}



	/**
	 *	Extracts an HTTP code from the given response header.
	 *
	 *	@param string $header Reponse header.
	 *	@return int HTTP code.
	 */
	protected function _extractHttpCode($header) {
		preg_match(
			'#^HTTP/[0-9\.]+\s(?P<code>[0-9]+)#i',
			$header,
			$matches
		);

		return isset($matches['code'])
			? $matches['code']
			: $this->_defaultCode;
	}
}
