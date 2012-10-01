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

class Media {

	/**
	 *	Embed data, indexed by property name. Providers must try to fill these
	 *	properties with appropriate data before adding their own, to
	 *	ensure consistency accross the API.
	 *
	 *	These default properties are gathered from the OEmbed and OpenGraph
	 *	protocols, and provide all the basic informations needed to embed a
	 *	media.
	 *
	 *	@var array
	 */

	protected $_properties = array( );



	/**
	 *	OEmbed type
	 *	OG type
	 */

	public $type = '';



	/**
	 *	OEmbed version
	 */

	public $version = '';



	/**
	 *	OEmbed title
	 *	OG title
	 */

	public $title = '';



	/**
	 *	Sometimes provided in OEmbed (i.e. Vimeo)
	 *	OG description
	 */

	public $description = '';



	/**
	 *	OEmbed author_name
	 */

	public $authorName = '';



	/**
	 *	OEmbed author_url
	 */

	public $authorUrl = '';



	/**
	 *	OEmbed provider_name
	 *	OG site_name
	 */

	public $providerName = '';



	/**
	 *	OEmbed provider_url
	 */

	public $providerUrl = '';



	/**
	 *	OEmbed cache_age
	 */

	public $cacheAge = '';



	/**
	 *	OEmbed thumbnail_url
	 *	OG image
	 *	OG image:url
	 */

	public $thumbnailUrl = '';



	/**
	 *	OEmbed thumbnail_width
	 */

	public $thumbnailWidth = '';



	/**
	 *	OEmbed thumbnail_height
	 */

	public $thumbnailHeight = '';



	/**
	 *	OEmbed html
	 */

	public $html = '';



	/**
	 *	OEmbed width
	 *	OG image:width
	 *	OG video:width
	 */

	public $width = '';



	/**
	 *	OEmbed height
	 *	OG image:height
	 *	OG video:height
	 */

	public $height = '';



	/**
	 *	OEmbed url
	 *	OG url
	 */

	public $url = '';



	/**
	 *	Constructs a Media from the given dataset.
	 *	If the property names in the dataset doesn't match the standard one,
	 *	the $correspondances array can be used to specify a reindexation
	 *	schema.
	 *
	 *	@see Media::$_data
	 *	@see Media::_reindex( )
	 *	@param array $properties An array of embed informations.
	 *	@param array $correspondances An array of index correspondances.
	 */

	public function __construct( array $properties, array $correspondances = array( )) {

		if ( !empty( $correspondances )) {
			$properties = $this->_reindex( $properties, $correspondances );
		}

		foreach ( $properties as $property => $value ) {
			if ( isset( $this->{$property})) {
				$this->{$property} = $value;
			} else {
				$this->_properties[ $property ] = $value;
			}
		}
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
	 *
	 */

	public function __isset( $property ) {

		return $this->hasCustomProperty( $property );
	}



	/**
	 *
	 */

	public function __get( $property ) {

		return $this->getCustomProperty( $property );
	}



	/**
	 *
	 */

	public function __set( $property, $value ) {

		return $this->setCustomProperty( $property, $value );
	}



	/**
	 *	Returns if there is any value for the given property.
	 *
	 *	@param string $property Property name.
	 *	@param boolean True if the property exists, otherwise false.
	 */

	public function hasCustomProperty( $property ) {

		return isset( $this->_properties[ $property ]);
	}



	/**
	 *	Returns all the custom property.
	 *
	 *	@return array Custom properties.
	 */

	public function getCustomProperties( ) {

		return $this->_properties;
	}



	/**
	 *	Returns the value of the given property.
	 *
	 *	@param string $property Property name.
	 *	@return mixed The property value, or null if the property doesn't exists.
	 */

	public function getCustomProperty( $property ) {

		return $this->hasCustomProperty( $property )
			? $this->_properties[ $property ]
			: null;
	}



	/**
	 *	Sets the value of one or many properties.
	 *
	 *	@param string $property Property name.
	 *	@param string $value New value.
	 */

	public function setCustomProperty( $property, $value ) {

		$this->_properties[ $property ] = $value;
	}
}
