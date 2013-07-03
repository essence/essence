<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Dom;



/**
 *	Handles HTML related operations.
 *
 *	@package fg.Essence.Dom
 */

class DomDocument implements \fg\Essence\Dom {

	/**
	 *	{@inheritDoc}
	 */

	public function extractAttributes( $html, array $options ) {

		$reporting = error_reporting( 0 );
		$Document = \DOMDocument::loadHTML( $html );
		error_reporting( $reporting );

		if ( $Document === false ) {
			throw new \fg\Essence\Exception( 'Unable to load HTML document.' );
		}

		$options = self::_format( $options, array( ));
		$data = array( );

		foreach ( $options as $tagName => $requiredAttributes ) {
			$data[ $tagName ] = array( );
			$requiredAttributes = self::_format( $requiredAttributes, '' );

			$tags = $Document->getElementsByTagName( $tagName );

			if ( $tags->length > 0 ) {
				foreach ( $tags as $Tag ) {
					if ( $Tag->hasAttributes( )) {
						$attributes = self::_extractAttributesFromTag(
							$Tag,
							$requiredAttributes
						);

						if ( !empty( $attributes )) {
							$data[ $tagName ][ ] = $attributes;
						}
					}
				}
			}
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



	/**
	 *	Formats the given attributes for safer later use. Every element that
	 *	is numerically indexed becomes a key, given $default as value.
	 *
	 *	@param array $attributes The array to format.
	 *	@param string $default Default value.
	 *	@return array The formatted array.
	 */

	protected function _format( $attributes, $default ) {

		if ( is_string( $attributes )) {
			return array( $attributes => $default );
		}

		$formatted = array( );

		foreach ( $attributes as $key => $value ) {
			if ( is_numeric( $key )) {
				$key = $value;
				$value = $default;
			}

			$formatted[ $key ] = $value;
		}

		return $formatted;
	}
}
