<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence\Provider;



/**
 *
 */

class OEmbed extends \Essence\Provider {

	/**
	 *
	 */

	protected $_endpoint;



	/**
	 *
	 */

	public function __construct( $pattern, $endpoint ) {

		parent::__construct( $pattern );

		$this->_endpoint = $endpoint;
	}



	/**
	 *	Strips arguments and anchors by default.
	 */

	protected function _prepare( $url ) {

		$questionMark = strpos( $url, '?' );

		if ( $questionMark === false ) {
			$sharp = strpos( $url, '#' );

			if ( $sharp !== false ) {
				$url = substr( $url, 0, $sharp );
			}
		} else {
			$url = substr( $url, 0, $questionMark );
		}

		return $url;
	}



	/**
	 *
	 */

	protected function _fetch( $url ) {

		$endpoint = sprintf( $this->_endpoint, urlencode( $url ));

		return json_decode( file_get_contents( $endpoint ), true );
	}
}
