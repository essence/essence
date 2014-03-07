<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Dom\Parser;

use Essence\Dom\Parser;
use Essence\Exception;
use Essence\Utility\Hash;
use DomDocument;
use DomNode;



/**
 *	Handles HTML related operations through DomDocument.
 *
 *	@package Essence.Dom.Parser
 */

class Native implements Parser {

	/**
	 *	{@inheritDoc}
	 */

	public function extractAttributes( $html, array $options ) {

		$Document = $this->_document( $html );
		$options = Hash::normalize( $options, [ ]);
		$data = [ ];

		foreach ( $options as $name => $required ) {
			$tags = $Document->getElementsByTagName( $name );
			$required = Hash::normalize(( array )$required, '' );
			$data[ $name ] = [ ];

			foreach ( $tags as $Tag ) {
				if ( $Tag->hasAttributes( )) {
					$attributes = $this->_extractAttributesFromTag(
						$Tag,
						$required
					);

					if ( !empty( $attributes )) {
						$data[ $name ][ ] = $attributes;
					}
				}
			}
		}

		return $data;
	}



	/**
	 *	Builds and returns a DomDocument from the given HTML source.
	 *
	 *	@param string $html HTML source.
	 *	@return DomDocument DomDocument.
	 */

	protected function _document( $html ) {

		$reporting = error_reporting( 0 );
		$Document = DomDocument::loadHTML( $html );
		error_reporting( $reporting );

		if ( $Document === false ) {
			throw new Exception( 'Unable to load HTML document.' );
		}

		return $Document;
	}



	/**
	 *	Extracts attributes from the given tag.
	 *
	 *	@param DOMNode $Tag Tag to extract attributes from.
	 *	@param array $required Required attributes.
	 *	@return array Extracted attributes.
	 */

	protected function _extractAttributesFromTag( DOMNode $Tag, array $required ) {

		$attributes = [ ];

		foreach ( $Tag->attributes as $name => $Attribute ) {
			if ( !empty( $required )) {
				if ( isset( $required[ $name ])) {
					$pattern = $required[ $name ];

					if ( $pattern && !preg_match( $pattern, $Attribute->value )) {
						return [ ];
					}
				} else {
					continue;
				}
			}

			$attributes[ $name ] = $Attribute->value;
		}

		$diff = array_diff_key( $required, $attributes );

		return empty( $diff )
			? $attributes
			: [ ];
	}
}
