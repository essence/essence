<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

use fg\Essence\Cache\Consumer as CacheConsumer;
use fg\Essence\Dom\Consumer as DomConsumer;
use fg\Essence\Http\Consumer as HttpConsumer;



/**
 *	Gathers embed informations from URLs.
 *
 *	@package fg.Essence
 */

class Essence {

	use CacheConsumer;
	use DomConsumer;
	use HttpConsumer;



	/**
	 *	A collection of providers to query.
	 *
	 *	@var fg\Essence\ProviderCollection
	 */

	protected $_Collection = null;



	/**
	 *	Configuration options.
	 *
	 *	### Options
	 *
	 *	- 'urlPattern' string A pattern to match URLs.
	 *
	 *	@var array
	 */

	protected $_config = array(
		// http://daringfireball.net/2010/07/improved_regex_for_matching_urls
		'urlPattern' => '#(?<!=["\'])(?<url>(?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'"\.,<>?«»“”‘’]))#i',
	);



	/**
	 *	Constructor.
	 *
	 *	@param fg\Essence\ProviderCollection|array|string $providers An instance
	 *		of ProviderCollection, or a configuration array/file to pass to a
	 *		new instance.
	 *	@param array $config Essence configuration.
	 */

	public function __construct( $providers = array( ), array $config = array( )) {

		$this->_Collection = ( $providers instanceof ProviderCollection )
			? $providers
			: new ProviderCollection( $providers );

		$this->_config = array_merge( $this->_config, $config );
	}



	/**
	 *	Extracts embeddable URLs from either an URL or an HTML source.
	 *	If the URL can be parsed directly by one of the registered providers,
	 *	it is returned as is. Otherwise, the page is parsed to find such URLs.
	 *
	 *	@param string $source The URL or HTML source to be extracted.
	 *	@return array An array of extracted URLs.
	 */

	public function extract( $source ) {

		$key = 'extract' . $source;

		return $this->_cache( )->has( $key )
			? $this->_cache( )->get( $key )
			: $this->_cache( )->set( $key, $this->_extract( $source ));
	}



	/**
	 *	@see extract( )
	 */

	protected function _extract( $source ) {

		if ( filter_var( $source, FILTER_VALIDATE_URL )) {
			if ( $this->_Collection->hasProvider( $source )) {
				return array( $source );
			}

			$source = $this->_http( )->get( $source );
		}

		$urls = $this->_extractUrls( $source );
		$embeddable = array( );

		foreach ( $urls as $url ) {
			if (	$this->_Collection->hasProvider( $url )) {
				$embeddable[ ] = $url;
			}
		}

		return array_unique( $embeddable );
	}



	/**
	 *	Extracts URLs from an HTML source.
	 *
	 *	@param string $html The HTML source to extract URLs from.
	 *	@return array Extracted URLs.
	 */

	protected function _extractUrls( $html ) {

		$options = array(
			'a' => 'href',
			'embed' => 'src',
			'iframe' => 'src'
		);

		$attributes = $this->_dom( )->extractAttributes( $html, $options );
		$urls = array( );

		foreach ( $options as $tagName => $attributeName ) {
			foreach ( $attributes[ $tagName ] as $tag ) {
				$urls[ ] = $tag[ $attributeName ];
			}
		}

		return $urls;
	}



	/**
	 *	Fetches embed informations from the given URL.
	 *
	 *	This method now supports an array of options that can be interpreted
	 *	at will by the providers.
	 *
	 *	Thanks to Peter Niederlag (https://github.com/t3dev) for his request
	 *	(https://github.com/felixgirault/essence/pull/1).
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return Media Embed informations.
	 */

	public function embed( $url, array $options = array( )) {

		$key = 'embed' . $url;

		if ( $options ) {
			$key .= json_encode( $options );
		}

		return $this->_cache( )->has( $key )
			? $this->_cache( )->get( $key )
			: $this->_cache( )->set( $key, $this->_embed( $url, $options ));
	}



	/**
	 *	@see embed( )
	 */

	protected function _embed( $url, array $options ) {

		$providers = $this->_Collection->providers( $url );
		$Media = null;

		foreach ( $providers as $Provider ) {
			if ( $Media = $Provider->embed( $url, $options )) {
				break;
			}
		}

		return $Media;
	}



	/**
	 *	Fetches embed informations from the given URLs.
	 *
	 *	@param array $urls An array of URLs to fetch informations from.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return array An array of embed informations, indexed by URL.
	 */

	public function embedAll( array $urls, array $options = array( )) {

		$medias = array( );

		foreach ( $urls as $url ) {
			$medias[ $url ] = $this->embed( $url, $options );
		}

		return $medias;
	}



	/**
	 *	Replaces URLs in the given text by media informations if they point on
	 *	an embeddable resource.
	 *	By default, links will be replaced by the html property of Media.
	 *	If $callback is a callable function, it will be used to generate
	 *	replacement strings, given a Media object.
	 *
	 *	@code
	 *	$text = $Essence->replace( $text, function( $Media ) {
	 *		return '<div class="title">' . $Media->title . '</div>';
	 *	});
	 *	@endcode
	 *
	 *	This behavior should make it easy to integrate third party templating
	 *	engines.
	 *	The pattern to match urls can be configured the 'urlPattern' configuration
	 *	option.
	 *
	 *	Thanks to Stefano Zoffoli (https://github.com/stefanozoffoli) for his
	 *	idea (https://github.com/felixgirault/essence/issues/4).
	 *
	 *	@param string $text Text in which to replace URLs.
	 *	@param callable $callback Templating callback.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return string Text with replaced URLs.
	 */

	public function replace( $text, $callback = null, array $options = array( )) {

		return preg_replace_callback(
			$this->_config['urlPattern'],
			function ( $matches ) use ( $callback, $options ) {
				$Media = $this->embed( $matches['url'], $options );

				if ( $Media === null ) {
					return $matches['url'];
				}

				return is_callable( $callback )
					? call_user_func( $callback, $Media )
					: $Media->get( 'html' );
			},
			$text
		);
	}
}
