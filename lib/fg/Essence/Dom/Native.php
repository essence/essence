<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Dom;

use fg\Essence\Dom;
use fg\Essence\Exception;
use fg\Essence\Utility\Hash;



/**
 *	Handles HTML related operations.
 *
 *	@package fg.Essence.Dom
 */

class Native implements Dom {

	/**
	 *	{@inheritDoc}
	 */

	public function extractAttributes( $html, array $options ) {

		$reporting = error_reporting( 0 );
		$Document = \Native::loadHTML( $html );
		error_reporting( $reporting );

		if ( $Document === false ) {
			throw new Exception( 'Unable to load HTML document.' );
		}

		$options = Hash::normalize( $options, array( ));
		$data = array( );

		foreach ( $options as $tag => $required ) {
			$tags = $Document->getElementsByTagName( $tag );
			$required = Hash::normalize( $required, '' );
			$data[ $tag ] = array( );

			foreach ( $tags as $Tag ) {
				if ( $Tag->hasAttributes( )) {
					$attributes = $this->_extractAttributesFromTag(
						$Tag,
						$required
					);

					if ( !empty( $attributes )) {
						$data[ $tag ][ ] = $attributes;
					}
				}
			}
		}

		return $data;
	}



	/**
	 *	Extracts attributes from the given tag.
	 *
	 *	@param \DOMNode $Tag Tag to extract attributes from.
	 *	@param array $required Required attributes.
	 *	@return array Extracted attributes.
	 */

	protected function _extractAttributesFromTag( \DOMNode $Tag, array $required ) {

		$attributes = array( );

		foreach ( $Tag->attributes as $a => $Attribute ) {
			if ( !empty( $required )) {
				if ( isset( $required[ $Attribute->name ])) {
					$pattern = $required[ $Attribute->name ];

					if ( $pattern && !preg_match( $pattern, $Attribute->value )) {
						return array( );
					}
				} else {
					continue;
				}
			}

			$attributes[ $Attribute->name ] = $Attribute->value;
		}

		$diff = array_diff_key( $required, $attributes );

		return empty( $diff )
			? $attributes
			: array( );
	}
}
