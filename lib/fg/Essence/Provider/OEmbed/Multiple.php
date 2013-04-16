<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Provider\OEmbed;



/**
 *	Multiple provider.
 *
 *	@package fg.Essence.Provider.OEmbed
 */

class Multiple extends \fg\Essence\Provider\OEmbed {

	/**
	 *	Default providers.
	 *
	 *	### format
	 *
	 *	- 'providerName' - Name of the provider.
	 *		- 'pattern' - URL pattern.
	 *		- 'endpoint' - OEmbed endpoint.
	 *		- 'format' - OEmbed response format.
	 *
	 *	@var array
	 */

	protected $_defaults = array(
		'providers' => array(
			'Flickr' => array(
				'pattern' => '#flickr\.com/photos/[a-zA-Z0-9@\\._]+/[0-9]+#i',
				'endpoint' => 'http://flickr.com/services/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Hulu' => array(
				'pattern' => '#hulu\.com/watch/.+#i',
				'endpoint' => 'http://www.hulu.com/api/oembed.json?url=%s',
				'format' => self::json
			),
			'Revision3' => array(
				'pattern' => '#revision3\.com/[a-z0-9]+/.+#i',
				'endpoint' => 'http://revision3.com/api/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Scribd' => array(
				'pattern' => '#scribd\.com/doc/[0-9]+/.+#i',
				'endpoint' => 'http://www.scribd.com/services/oembed?format=json&url=%s',
				'format' => self::json
			),
			'SoundCloud' => array(
				'pattern' => '#soundcloud\.com/[a-zA-Z0-9-]+/[a-zA-Z0-9-]+#i',
				'endpoint' => 'http://soundcloud.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Twitter' => array(
				'pattern' => '#twitter\.com/[a-zA-Z0-9_]+/status/.+#i',
				'endpoint' => 'https://api.twitter.com/1/statuses/oembed.json?url=%s',
				'format' => self::json
			)
		)
	);



	/**
	 *	A cache for found providers.
	 *
	 *	@var fg\Essence\Cache\Volatile
	 */

	protected $_Cache = null;



	/**
	 *	{@inheritDoc}
	 */

	public function __construct( array $options = array( )) {

		parent::__construct( $options );

		$this->_Cache = new \fg\Essence\Cache\Volatile( );
	}



	/**
	 *	{@inheritDoc}
	 */

	public function canEmbed( $url ) {

		$provider = $this->_findProvider( $url );

		return !empty( $provider );
	}



	/**
	 *	Returns the name of the first provider which can embed the given URL.
	 *
	 *	@param string $url The URL to embed.
	 *	@return string Provider name, or an empty string if none was found.
	 */

	protected function _findProvider( $url ) {

		if ( $this->_Cache->has( $url )) {
			return $this->_Cache->get( $url );
		}

		$provider = '';

		foreach ( $this->_options['providers'] as $name => $settings ) {
			if (( boolean ) preg_match( $settings['pattern'], $url )) {
				$provider = $name;
				break;
			}
		}

		$this->_Cache->set( $url, $provider );
		return $provider;
	}



	/**
	 *	{@inheritDoc}
	 */

	protected function _embed( $url, $options ) {

		$provider = $this->_findProvider( $url );

		if ( empty( $provider )) {
			return null;
		}

		extract( $this->_options['providers'][ $provider ]);

		return $this->_embedEndpoint(
			sprintf( $endpoint, urlencode( $url )),
			$format,
			$options
		);
	}
}
