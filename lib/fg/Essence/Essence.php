<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

use fg\Essence\Cache\Volatile;
use fg\Essence\Cache\Consumer as CacheConsumer;
use fg\Essence\Dom\Native;
use fg\Essence\Http\Curl;
use fg\Essence\Utility\Registry;



/**
 *	Gathers embed informations from URLs.
 *
 *	@package fg.Essence
 */

class Essence {

	use CacheConsumer;



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
	 *	- 'varPattern' string A pattern to match template's variables.
	 *
	 *	@var array
	 */

	protected $_config = array(
		// http://daringfireball.net/2010/07/improved_regex_for_matching_urls
		'urlPattern' => '#(?=(\b)\b|[^"])?(?<url>(?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'"\.,<>?«»“”‘’]))#i',
		'varPattern' => '#%(?<property>[\s\S]+?)%#'
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

		$this->_checkEnvironment( );

		$this->_config = array_merge( $this->_config, $config );
		$this->_Collection = ( $providers instanceof ProviderCollection )
			? $providers
			: new ProviderCollection( $providers );

		$this->setCache( Registry::get( 'cache' ));
	}



	/**
	 *	Checks the execution environment.
	 */

	public function _checkEnvironment( ) {

		if ( !Registry::has( 'cache' )) {
			Registry::register( 'cache', new Volatile( ));
		}

		if ( !Registry::has( 'dom' )) {
			Registry::register( 'dom', new Native( ));
		}

		if ( !Registry::has( 'http' )) {
			Registry::register( 'http', new Curl( ));
		}
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

		return $this->_Cache->has( $source )
			? $this->_Cache->get( $source )
			: $this->_Cache->set( $source, $this->_extract( $source ));
	}



	/**
	 *	@see extract( )
	 */

	protected function _extract( $source ) {

		if ( filter_var( $source, FILTER_VALIDATE_URL )) {
			if ( $this->_Collection->hasProvider( $source )) {
				return array( $source );
			}

			$source = Registry::get( 'http' )->get( $source );
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

		$attributes = Registry::get( 'dom' )->extractAttributes( $html, $options );
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

		$key = $url;

		if ( $options ) {
			$key .= json_encode( $options );
		}

		return $this->_Cache->has( $key )
			? $this->_Cache->get( $key )
			: $this->_Cache->set( $key, $this->_embed( $url, $options ));
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
	 *	Replaces URLs in the given text by media informations if they point
	 *	on an embeddable resource.
	 *	The template parameter controls how informations will replace the
	 *	URL. Any property of a media object can be used. For example, to
	 *	replace a URL with the title and HTML code of a resource, one could
	 *	use this template:
	 *
	 *	<div>
	 *		<span>%title%</span>
	 *		<div>%html%</div>
	 *	</div>
	 *
	 *	By default, links will be replaced by the html property of Media.
	 *	The behavior of this method can method can be customized with the
	 *	'urlPattern' and 'varPattern' configuration options.
	 *
	 *	Thanks to Stefano Zoffoli (https://github.com/stefanozoffoli) for his
	 *	idea (https://github.com/felixgirault/essence/issues/4).
	 *
	 *	@param string $text Text in which to replace URLs.
	 *	@param string $template Replacement template.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return string Text with replaced URLs.
	 */

	public function replace( $text, $template = '', array $options = array( )) {

		return preg_replace_callback(
			$this->_config['urlPattern'],
			function ( $matches ) use ( $template, $options ) {
				$Media = $this->embed( $matches['url'], $options );
				$replacement = '';

				if ( $Media !== null ) {
					if ( empty( $template )) {
						$replacement = $Media->get( 'html' );
					} else {
						$replacement = preg_replace_callback(
							$this->_config['varPattern'],
							function( $matches ) use ( &$Media ) {
								return $Media->get( $matches['property']);
							},
							$template
						);
					}
				}

				return $replacement;
			},
			$text
		);
	}
}
