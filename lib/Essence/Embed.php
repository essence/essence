<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence;



/**
 *	Stores informations about an embed response.
 *	This class is useful to ensure that any response from any provider will
 *	follow the same conventions.
 *
 *	@package Essence
 */

class Embed {

	/**
	 *	A constant representing all properties.
	 *
	 *	@see Embed::get( )
	 *	@var string
	 */

	const all = 'all';



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

	protected $_data = array(

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
		'url' => '',	

		/*
		// OG image:secure_url
		// OG video:secure_url
		// OG audio:secure_url
		'secureUrl' => '',	
		
		// OG image:type
		// OG video:type
		// OG audio:type
		'mime' => '',			

		// OG locale
		'locale' => '',			

		// OG locale:alternate
		'localeAlternate' => ''
		*/
	);



	/**
	 *	Constructs an Embed from the given dataset.
	 *	If the property names in the dataset doesn't match the standard one,
	 *	the $correspondances array can be used to specify a reindexation
	 *	schema.
	 *
	 *	@see Embed::$_data
	 *	@see Embed::_reindex( )
	 *	@param array $data An array of embed informations.
	 *	@param array $correspondances An array of index correspondances.
	 */

	public function __construct( array $data, array $correspondances = array( )) {

		if ( !empty( $correspondances )) {
			$data = $this->_reindex( $data, $correspondances );
		}

		$this->_data = array_merge( $this->_data, $data );
	}



	/**
	 *	Reindexes a set of data, according to the given correspondances.
	 *	
	 *	@param array $data The set of data to be reindexed.
	 *	@param array $correspondances An array of index correspondances of the
	 *		form `array( 'currentIndex' => 'newIndex' )`.
	 */

	protected function _reindex( array $data, array $correspondances ) {
		
		$result = $data;

		foreach ( $correspondances as $from => $to ) {
			if ( isset( $data[ $from ])) {
				$result[ $to ] = $data[ $from ];
			}
		}

		return $result;
	}



	/**
	 *	Returns if there is any value for the given property.
	 *
	 *	@param string $property Property name.
	 *	@param boolean True if the property exists, otherwise false.
	 */

	public function has( $property ) {

		return (
			isset( $this->_data[ $property ])
			&& !empty( $this->_data[ $property ])
		);
	}



	/**
	 *	Returns the value of the given property. 
	 *
	 *	@param string $property Property name, or Embed::all to retrieve all
	 *		the properties.
	 *	@return mixed False if the property doesn't exists. Otherwise, the
	 *		property value, or an array of all properties if all properties
	 *		were requested.
	 */

	public function get( $property = Embed::all ) {

		if ( $property === Embed::all ) {
			return $this->_data;
		} else {
			return $this->has( $property )
				? $this->_data[ $property ]
				: false;
		}
	}



	/**
	 *	Sets the value of the given property.
	 *
	 *	@param string $property Property name.
	 *	@param string New value.
	 */

	public function set( $property, $value ) {

		$this->_data[ $property ] = $value;
	}
}