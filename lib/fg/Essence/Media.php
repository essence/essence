<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	Stores informations about an embed response.
 *	This class is useful to ensure that any response from any provider will
 *	follow the same conventions.
 *
 *	@package fg.Essence
 */

class Media implements \IteratorAggregate {

	/**
	 *	Embed data, indexed by property name. Providers must try to fill these
	 *	default properties with appropriate data before adding their own, to
	 *	ensure consistency accross the API.
	 *
	 *	These default properties are gathered from the OEmbed and OpenGraph
	 *	protocols, and provide all the basic informations needed to embed a
	 *	media.
	 *
	 *	@var array
	 */

	public $properties = array(

		// OEmbed type
		// OG type
		'type' => '',

		// OEmbed version
		'version' => '',

		// OEmbed title
		// OG title
		'title' => '',

		// Sometimes provided in OEmbed (i.e. Vimeo)
		// OG description
		'description' => '',

		// OEmbed author_name
		'authorName' => '',

		// OEmbed author_url
		'authorUrl' => '',

		// OEmbed provider_name
		// OG site_name
		'providerName' => '',

		// OEmbed provider_url
		'providerUrl' => '',

		// OEmbed cache_age
		'cacheAge' => '',

		// OEmbed thumbnail_url
		// OG image
		// OG image:url
		'thumbnailUrl' => '',

		// OEmbed thumbnail_width
		'thumbnailWidth' => '',

		// OEmbed thumbnail_height
		'thumbnailHeight' => '',

		// OEmbed html
		'html' => '',

		// OEmbed width
		// OG image:width
		// OG video:width
		'width' => '',

		// OEmbed height
		// OG image:height
		// OG video:height
		'height' => '',

		// OEmbed url
		// OG url
		'url' => ''
	);



	/**
	 *	Constructs a Media from the given dataset.
	 *
	 *	@see Media::$properties
	 *	@param array $properties An array of media informations.
	 */

	public function __construct( array $properties ) {

		$this->properties = array_merge( $this->properties, $properties );
	}



	/**
	 *	@see has( )
	 */

	public function __isset( $name ) {

		return $this->has( $name );
	}



	/**
	 *	@see get( )
	 */

	public function __get( $name ) {

		return $this->get( $name );
	}



	/**
	 *	@see set( )
	 */

	public function __set( $name, $value ) {

		return $this->set( $name, $value );
	}



	/**
	 *	Returns if there is any value for the given property.
	 *
	 *	@param string $property Property name.
	 *	@param boolean True if the property exists, otherwise false.
	 */

	public function has( $property ) {

		return isset( $this->properties[ $property ]);
	}



	/**
	 *	An alias for has( ).
	 *
	 *	@see has( ).
	 *	@deprecated Since 1.4.2.
	 */

	public function hasProperty( $name ) {

		return $this->has( $name );
	}



	/**
	 *	Returns the value of the given property.
	 *
	 *	@param string $property Property name.
	 *	@param mixed $default Default value to be returned in case the property
	 *		doesn't exists.
	 *	@return mixed The property value, or $default.
	 */

	public function get( $property, $default = null ) {

		return $this->has( $property )
			? $this->properties[ $property ]
			: $default;
	}



	/**
	 *	An alias for get( ).
	 *
	 *	@see get( ).
	 *	@deprecated Since 1.4.2.
	 */

	public function property( $property, $default = null ) {

		return $this->get( $property, $default );
	}



	/**
	 *	Sets the value of a property.
	 *
	 *	@param string $property Property name.
	 *	@param string $value New value.
	 */

	public function set( $property, $value ) {

		$this->properties[ $property ] = $value;
	}



	/**
	 *	An alias for set( ).
	 *
	 *	@see set( ).
	 *	@deprecated Since 1.4.2.
	 */

	public function setProperty( $property, $value ) {

		$this->set( $property, $value );
	}



	/**
	 *	Returns an iterator for the media properties.
	 *
	 *	@return ArrayIterator Iterator.
	 */

	public function getIterator( ) {

		return new \ArrayIterator( $this->properties );
	}
}
