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
	 *	If the property names in the dataset doesn't match the standard one,
	 *	the $correspondances array can be used to specify a reindexation
	 *	schema.
	 *
	 *	@see Media::$properties
	 *	@see Media::_reindex( )
	 *	@param array $properties An array of media informations.
	 *	@param array $correspondances An array of indices correspondances.
	 */

	public function __construct( array $properties, array $correspondances = array( )) {

		if ( !empty( $correspondances )) {
			$properties = $this->_reindex( $properties, $correspondances );
		}

		$this->properties = $properties;
	}



	/**
	 *	Reindexes a set of properties, according to the given correspondances.
	 *
	 *	@param array $properties The set of properties to be reindexed.
	 *	@param array $correspondances An array of index correspondances of the
	 *		form `array( 'currentIndex' => 'newIndex' )`.
	 */

	protected function _reindex( array $properties, array $correspondances ) {

		$result = $properties;

		foreach ( $correspondances as $from => $to ) {
			if ( isset( $properties[ $from ])) {
				$result[ $to ] = $properties[ $from ];
			}
		}

		return $result;
	}



	/**
	 *	@see hasProperty( )
	 */

	public function __isset( $name ) {

		return $this->hasProperty( $name );
	}



	/**
	 *	@see property( )
	 */

	public function __get( $name ) {

		return $this->property( $name );
	}



	/**
	 *	@see setProperty( )
	 */

	public function __set( $name, $value ) {

		return $this->setProperty( $name, $value );
	}



	/**
	 *	Returns if there is any value for the given property.
	 *
	 *	@param string $name Property name.
	 *	@param boolean True if the property exists, otherwise false.
	 */

	public function hasProperty( $name ) {

		return isset( $this->properties[ $name ]);
	}



	/**
	 *	Returns the value of the given property.
	 *
	 *	@param string $name Property name.
	 *	@return mixed The property value, or null if the property doesn't exists.
	 */

	public function property( $name ) {

		return $this->hasProperty( $name )
			? $this->properties[ $name ]
			: null;
	}



	/**
	 *	Returns all properties.
	 *
	 *	@return array Properties.
	 */

	public function properties( ) {

		return $this->properties;
	}



	/**
	 *	Sets the value of a property.
	 *
	 *	@param string $name Property name.
	 *	@param string $value New value.
	 */

	public function setProperty( $name, $value ) {

		$this->properties[ $name ] = $value;
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
