<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;



/**
 *	Stores informations about a media.
 *	This class is useful to ensure that any response from any provider will
 *	follow the same conventions.
 */
class Media implements IteratorAggregate, JsonSerializable {

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
	protected $_properties = [
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
	];



	/**
	 *	Constructs a Media from the given dataset.
	 *
	 *	@see $properties
	 *	@param array $properties An array of media informations.
	 */
	public function __construct(array $properties = []) {
		$this->configure($properties);
	}



	/**
	 *	@see has()
	 */
	public function __isset($property) {
		return $this->has($property);
	}



	/**
	 *	@see get()
	 */
	public function __get($property) {
		return $this->get($property);
	}



	/**
	 *	@see set()
	 */
	public function __set($property, $value) {
		$this->set($property, $value);
	}



	/**
	 *	Returns if there is any value for the given property.
	 *
	 *	@param string $property Property name.
	 *	@param boolean True if the property exists, otherwise false.
	 */
	public function has($property) {
		return !empty($this->_properties[$property]);
	}



	/**
	 *	Returns the value of the given property.
	 *
	 *	@param string $property Property name.
	 *	@param mixed $default Default value to be returned in case the property
	 *		doesn't exists.
	 *	@return mixed The property value, or $default.
	 */
	public function get($property, $default = null) {
		return isset($this->_properties[$property])
			? $this->_properties[$property]
			: $default;
	}



	/**
	 *	Sets the value of the given property.
	 *
	 *	@param string $property Property name.
	 *	@param string $value New value.
	 */
	public function set($property, $value) {
		$this->_properties[$property] = $value;
		return $this;
	}



	/**
	 *	Sets the value of a property if it is empty.
	 *
	 *	@param string $property Property name.
	 *	@param string $default Default value.
	 */
	public function setDefault($property, $default) {
		if (!$this->has($property)) {
			$this->set($property, $default);
		}

		return $this;
	}



	/**
	 *	Sets default values.
	 *
	 *	@see setDefault()
	 *	@param array $properties Default properties.
	 */
	public function setDefaults(array $properties) {
		$this->_properties += $properties;
		return $this;
	}



	/**
	 *	Returns the entire set of properties.
	 *
	 *	@return array Properties.
	 */
	public function properties() {
		return $this->_properties;
	}



	/**
	 *	Returns the filled properties.
	 *
	 *	@return array Properties.
	 */
	public function filledProperties() {
		return array_filter($this->_properties);
	}



	/**
	 *	Sets the entire set of properties.
	 *
	 *	@param array $properties Properties to set.
	 */
	public function setProperties(array $properties) {
		$this->_properties = $properties;
		return $this;
	}



	/**
	 *	Merges the given properties with the current ones.
	 *
	 *	@param array $properties Properties to merge.
	 */
	public function configure(array $properties) {
		$this->_properties = $properties + $this->_properties;
		return $this;
	}



	/**
	 *	Returns an iterator for the media properties.
	 *
	 *	@return ArrayIterator Iterator.
	 */
	public function getIterator() {
		return new ArrayIterator($this->_properties);
	}



	/**
	 *	Returns serialized properties.
	 *
	 *	@return string JSON representation.
	 */
	public function jsonSerialize() {
		return $this->_properties;
	}
}
