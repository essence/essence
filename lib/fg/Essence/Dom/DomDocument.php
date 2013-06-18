<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Dom;

use fg\Essence\Dom;
use fg\Essence\Exception;
use fg\Essence\Utility\Set;



/**
 *	Handles HTML related operations.
 *
 *	@package fg.Essence.Dom
 */

class DomDocument implements Dom {

	/**
	 *	{@inheritDoc}
	 */

	public function extractAttributes( $html, array $options ) {

		$reporting = error_reporting( 0 );
		$Document = \DOMDocument::loadHTML( $html );
		error_reporting( $reporting );

		if ( $Document === false ) {
			throw new Exception( 'Unable to load HTML document.' );
		}

		$options = Set::normalize( $options, array( ));
		$data = array( );

		foreach ( $options as $tagName => $requiredAttributes ) {
			$data[ $tagName ] = array( );
			$tags = $Document->getElementsByTagName( $tagName );
			$requiredAttributes = Set::normalize( $requiredAttributes, '' );

			//if ( $tags->length > 0 ) {
				foreach ( $tags as $Tag ) {
					if ( $Tag->hasAttributes( )) {
						$attributes = $this->_extractAttributesFromTag(
							$Tag,
							$requiredAttributes
						);

						if ( !empty( $attributes )) {
							$data[ $tagName ][ ] = $attributes;
						}
					}
				}
			//}
		}

		return $data;
	}



	/**
	 *	Extracts attributes from the given tag.
	 *
	 *	@param \DOMElement $Tag Tag to extract attributes from.
	 *	@param array $requiredAttributes Required attributes.
	 *	@return array Extracted attributes.
	 */

	protected function _extractAttributesFromTag( \DOMElement $Tag, array $requiredAttributes ) {

		$attributes = array( );
		$length = $Tag->attributes->length;

		for ( $i = 0; $i < $length; $i++ ) {
			$attribute = $Tag->attributes->item( $i );

			if ( !empty( $requiredAttributes )) {
				if ( isset( $requiredAttributes[ $attribute->name ])) {
					$pattern = $requiredAttributes[ $attribute->name ];

					if ( !empty( $pattern )) {
						if ( !preg_match( $pattern, $attribute->value )) {
							return array( );
						}
					}
				} else {
					continue;
				}
			}

			$attributes[ $attribute->name ] = $attribute->value;
		}

		$diff = array_diff_key( $requiredAttributes, $attributes );

		return empty( $diff )
			? $attributes
			: array( );
	}
}
