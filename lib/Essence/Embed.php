<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license MIT
 */

namespace Essence;



/**
 *	Stores informations about an embed response.
 *	This structure is useful to ensure that any response from any provider
 *	follows the same schema.
 *
 *	@package Embed
 */

class Embed {

	/**
	 *	Standard keys to identify embed data.
	 *	They are gathered from OEmbed and the Open Graph protocol.
	 */

	public $_allowedKeys = array(

		// OEmbed type 
		// OG type
		'type',

		// OEmbed version
		'version',	

		// OEmbed title
		// OG title
		'title',		

		// Sometimes provided in OEmbed (i.e. Vimeo)
		// OG description
		'description',		

		// OEmbed author_name
		'authorName',		

		// OEmbed author_url
		'authorUrl',		

		// OEmbed provider_name 
		// OG site_name
		'providerName',	

		// OEmbed provider_url
		'providerUrl',		

		// OEmbed cache_age
		'cacheAge',		

		// OEmbed thumbnail_url
		// OG image
		// OG image:url
		'thumbnailUrl',	

		// OEmbed thumbnail_width
		'thumbnailWidth',	

		// OEmbed thumbnail_height
		'thumbnailHeight',	

		// OEmbed html
		'html',	

		// OEmbed width 
		// OG image:width 
		// OG video:width
		'width',			

		// OEmbed height 
		// OG image:height 
		// OG video:height
		'height',		

		// OEmbed url 
		// OG url
		'url',	

		// OG image:secure_url
		// OG video:secure_url
		// OG audio:secure_url
		'secureUrl',	
		
		// OG image:type
		// OG video:type
		// OG audio:type
		'mime',			

		// OG locale
		'locale',			

		// OG locale:alternate
		'localeAlternate'
	);



	/**
	 *	
	 */

	public $_data = array( );



	/**
	 *	Constructs a Representation from the given dataset.
	 *	Keys referencing the data must match the ones from the allowed keys.
	 *
	 *	@see Representation::$_allowedKeys
	 *	@param array $data An array of embed informations.
	 */

	public function __construct( array $data ) {

		foreach ( $this->_allowedKeys as $key ) {
			if ( isset( $data[ $key ])) {
				$this->_data = $data[ $key ];
			}
		}
	}



	/**
	 *	Returns if there is any data for the given key.
	 *
	 *	@param string $key Key.
	 *	@param boolean True if the key is set, otherwise false.
	 */

	public function __isset( $key ) {

		return isset( $this->_data[ $key ]);
	}



	/**
	 *	Returns the value of the given key.
	 *
	 *	@param string $key Key.
	 *	@param string Value.
	 */

	public function __get( $key ) {

		return isset( $this->_data[ $key ])
			? $this->_data[ $key ]
			: '';
	}



	/**
	 *	Sets a value for the given key.
	 *
	 *	@param string $key Key.
	 *	@param string Value.
	 */

	public function __set( $key ) {

		if ( in_array( $this->_allowedKeys[ $key ])) {
			$this->_data[ $key ] = $value;
		}
	}
}