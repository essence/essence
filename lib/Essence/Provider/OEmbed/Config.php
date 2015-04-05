<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider\OEmbed;

use Essence\Provider\OEmbed\Format;



/**
 *	Holds configuration of an oEmbed provider.
 */
class Config {

	/**
	 *	Endpoint.
	 *
	 *	@var string
	 */
	protected $_endpoint = '';



	/**
	 *	Format.
	 *
	 *	@var string
	 */
	protected $_format = '';



	/**
	 *	Constructor.
	 *
	 *	@param string $endpoint The OEmbed endpoint.
	 *	@param string $format The expected response format.
	 */
	public function __construct($endpoint = '', $format = Format::json) {
		$this->_endpoint = $endpoint;
		$this->_format = $format;
	}



	/**
	 *	Returns the endpoint.
	 *
	 *	@return string Endpoint.
	 */
	public function endpoint() {
		return $this->_endpoint;
	}



	/**
	 *	Sets the endpoint.
	 *
	 *	@param string $endpoint Endpoint.
	 */
	public function setEndpoint($endpoint) {
		$this->_endpoint = $endpoint;
	}



	/**
	 *	Returns the format.
	 *
	 *	@return string Format.
	 */
	public function format() {
		return $this->_format;
	}



	/**
	 *	Sets the format.
	 *
	 *	@param string $format Format.
	 */
	public function setFormat($format) {
		$this->_format = $format;
	}



	/**
	 *	Appends a set of options as parameters to the endpoint.
	 *
	 *	@param array $options Options to append.
	 */
	public function completeEndpoint($options) {
		$hasQuery = (strrpos($this->_endpoint, '?') === false);
		$separator = $hasQuery ? '?' : '&';

		$this->_endpoint .= $separator . http_build_query($options);
	}
}
