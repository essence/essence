<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence\Provider;



/**
 *
 */

class OpenGraph extends \Essence\Provider {

	/**
	 *
	 */

	public function __construct( $pattern ) {

		parent::__construct( $pattern );
	}



	/**
	 *
	 */

	protected function _fetch( $url ) {

		$html = file_get_contents( $url );

		if ( $html === false ) {
			return array( );
		}

		$properties = $this->_extract( $html );

		return $this->_check( $properties )
			? $this->_format( $properties )
			: array( );
	}



	/**
	 *
	 */

	protected function _extract( &$html ) {

		$properties = array( );
		$count = preg_match_all(
			'#<meta[^>]*property="og:(?P<property>[^"]+)"[^>]*content="(?P<content>[^"]+)"#',
			$html,
			$matches,
			PREG_SET_ORDER
		);

		if ( $count ) {
			foreach ( $matches as $match ) {
				$properties[ $match['property']] = $match['content'];
			}
		}

		return $properties;
	}



	/**
	 *
	 */

	protected function _check( $properties ) {

		return isset( $properties['url'])
			&& isset( $properties['title'])
			&& isset( $properties['image'])
			&& isset( $properties['video']);
	}



	/**
	 *
	 */

	protected function _format( $properties ) {

		return Utility::reindex(
			$properties,
			array(
				'url'		=> 'url',
				'video'		=> 'player_url',
				'video:width'	=> 'player_width',
				'video:height'	=> 'player_height',
				'image'		=> 'thumbnail_url',
				'title'		=> 'title'
			),
			true
		);
	}
}
