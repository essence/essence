<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@author Laughingwithu <Laughingwithu@gmail.com>
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
			'23hq' => array(
				'pattern' => '#23hq.com/.+/photo/.+#i',
				'endpoint' => 'http://www.23hq.com/23/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Blip.tv' => array(
				'pattern' => '#blip.tv/.+#i',
				'endpoint' => 'http://blip.tv/oembed?format=json&url=%s',
				'format' => self::json
			),			
			'Cacoo' => array(
				'pattern' => '#cacoo.com/.+#i',
				'endpoint' => 'http://cacoo.com/oembed.json?url=%s',
				'format' => self::json
			),
			'Chirb.it' => array(
				'pattern' => '#chirb.it/.+#i',
				'endpoint' => 'http://chirb.it/oembed.json?url=%s',
				'format' => self::json
			),
			'Clikthrough' => array(
				'pattern' => '#clikthrough\.com/theater/video/\d+#i',
				'endpoint' => 'http://clikthrough.com/services/oembed?format=json&url=%s',
				'format' => self::json
			),
			'CollegeHumor' => array(
				'pattern' => '#collegehumor.com/(video|embed)/.*#i',
				'endpoint' => 'http://www.collegehumor.com/oembed.json?url=%s',
				'format' => self::json
			),
			'Deviantart' => array(
				'pattern' => '#deviantart.com/.+#i',
				'endpoint' => 'http://backend.deviantart.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Dipity' => array(
				'pattern' => '#dipity.com/.+#i',
				'endpoint' => 'http://www.dipity.com/oembed/timeline?format=json&url=%s',
				'format' => self::json
			),
			'Flickr' => array(
				'pattern' => '#flickr\.com/photos/[a-zA-Z0-9@\\._]+/[0-9]+#i',
				'endpoint' => 'http://flickr.com/services/oembed?format=json&url=%s',
				'format' => self::json
			),
			'FunnyOrDie' => array(
				'pattern' => '#funnyordie\.com/videos/.*#i',
				'endpoint' => 'http://www.funnyordie.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Huffduffer' => array(
				'pattern' => '#huffduffer.com/[-.\w@]+/\d+#i',
				'endpoint' => 'http://huffduffer.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Hulu' => array(
				'pattern' => '#hulu\.com/watch/.+#i',
				'endpoint' => 'http://www.hulu.com/api/oembed.json?url=%s',
				'format' => self::json
			),
			'Ifixit' => array(
				'pattern' => '#ifixit.com/.*#i',
				'endpoint' => 'http://www.ifixit.com/Embed?format=json&url=%s',
				'format' => self::json
			),
			'Imgur' => array(
				'pattern' => '#(imgur\.com/(gallery|a)/.+|imgur\.com/.+)#i',
				'endpoint' => 'http://api.imgur.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Instagram' => array(
				'pattern' => '#instagr(\.am|am\.com)/p/.*#i',
				'endpoint' => 'http://api.instagram.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Mobypicture' => array(
				'pattern' => '#mobypicture.com/user/.+/view/.+#','moby.to/.+#i',
				'endpoint' => 'http://api.mobypicture.com/oEmbed?format=json&url=%s',
				'format' => self::json
			),
			'Official.fm' => array(
				'pattern' => '#official.fm/.+#i',
				'endpoint' => 'http://official.fm/services/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Polldaddy' => array(
				'pattern' => '#polldaddy\.com/.*#i',
				'endpoint' => 'http://polldaddy.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Qik' => array(
				'pattern' => '#qik\.com/\w+#i',
				'endpoint' => 'http://qik.com/api/oembed.json?url=%s',
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
			'Shoudio' => array(
				'pattern' => '#(shoudio.com/.+|shoud.io/.+)#i',
				'endpoint' => 'http://shoudio.com/api/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Sketchfab' => array(
				'pattern' => '#sketchfab.com/show/.+#i',
				'endpoint' => 'http://sketchfab.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'SlideShare' => array(
				'pattern' => '#slideshare\.net/.+/.+#i',
				'endpoint' => 'http://www.slideshare.net/api/oembed/2?format=json&url=%s',
				'format' => self::json
			),
			'SoundCloud' => array(
				'pattern' => '#soundcloud\.com/[a-zA-Z0-9-]+/[a-zA-Z0-9-]+#i',
				'endpoint' => 'http://soundcloud.com/oembed?format=json&url=%s',
				'format' => self::json
			),
			'Ted' => array(
				'pattern' => '#ted.com/talks/*+#i',
				'endpoint' => 'http://www.ted.com/talks/oembed.json?url=%s',
				'format' => self::json
			),
			'Twitter' => array(
				'pattern' => '#twitter\.com/[a-zA-Z0-9_]+/status/.+#i',
				'endpoint' => 'https://api.twitter.com/1/statuses/oembed.json?url=%s',
				'format' => self::json
			),
			'Vhx' => array(
				'pattern' => '#vhx.tv/.+#i',
				'endpoint' => 'http://vhx.tv/services/oembed.json?url=%s',
				'format' => self::json
			),
			'Viddler' => array(
				'pattern' => '#viddler.com/.+#i',
				'endpoint' => 'http://www.viddler.com/oembed/?url=%s',
				'format' => self::json
			),
			'Yfrog' => array(
				'pattern' => '#yfrog\.(com|ru|com\.tr|it|fr|co\.il|co\.uk|com\.pl|pl|eu|us)/.+#i',
				'endpoint' => 'http://www.yfrog.com/api/oembed?format=json&url=%s',
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
