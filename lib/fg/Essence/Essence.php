<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	Gathers embed informations from URLs.
 *
 *	@package fg.Essence
 */

class Essence {

	/**
	 *	A collection of providers to query.
	 *
	 *	@var \fg\Essence\ProviderCollection
	 */

	protected $_Collection = null;



	/**
	 *	The internal cache engine.
	 *
	 *	@var \fg\Essence\Cache
	 */

	protected $_Cache = null;



	/**
	 *	An array of catched exceptions.
	 *
	 *	@var array
	 */

	protected $_errors = array( );



	/**
	 *	Constructor.
	 *
	 *	@see ProviderCollection::load( )
	 *	@param array $providers An array of provider class names, relative to
	 *		the 'Provider' folder.
	 */

	public function __construct( array $providers = array( )) {

		$this->_checkEnvironment( );

		$this->_Collection = new ProviderCollection( $providers );
		$this->_Cache = Registry::get( 'cache' );
	}



	/**
	 *	Checks the execution environment.
	 */

	public function _checkEnvironment( ) {

		if ( !Registry::has( 'cache' )) {
			Registry::register( 'cache', new Cache\Volatile( ));
		}

		if ( !Registry::has( 'dom' )) {
			Registry::register( 'dom', new Dom\DomDocument( ));
		}

		if ( !Registry::has( 'http' )) {
			Registry::register( 'http', new Http\Curl( ));
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

		$key = 'extract' . $source;
		$cached = $this->_Cache->get( $key );

		if ( $cached !== null ) {
			return $cached;
		}

		try {
			$embeddable = $this->_extract( $source );
		} catch ( Exception $Exception ) {
			$this->_log( $Exception );
			return array( );
		}

		$this->_Cache->set( $key, $embeddable );
		return $embeddable;
	}



	/**
	 *	@see Essence::extract( )
	 */

	protected function _extract( $source ) {

		if ( filter_var( $source, FILTER_VALIDATE_URL )) {
			// if a provider can directly handle the URL, there is no more work to do.
			if ( $this->_Collection->hasProvider( $source )) {
				return array( $source );
			}

			$source = Registry::get( 'http' )->get( $source );
		}

		$urls = $this->_extractUrls( $source );
		$embeddable = array( );

		foreach ( $urls as $url ) {
			if (
				$this->_Collection->hasProvider( $url )
				&& !in_array( $url, $embeddable )
			) {
				$embeddable[ ] = $url;
			}
		}

		return $embeddable;
	}



	/**
	 *	Extracts URLs from an HTML source.
	 *
	 *	@param string $html The HTML source to extract URLs from.
	 *	@return array Extracted URLs.
	 */

	protected function _extractUrls( $html ) {

		$attributes = Registry::get( 'dom' )->extractAttributes(
			$html,
			array(
				'a' => 'href',
				'embed' => 'src',
				'iframe' => 'src'
			)
		);

		$urls = array( );

		foreach ( $attributes['a'] as $a ) {
			$urls[ ] = $a['href'];
		}

		foreach ( $attributes['embed'] as $embed ) {
			$urls[ ] = $embed['src'];
		}

		foreach ( $attributes['iframe'] as $iframe ) {
			$urls[ ] = $iframe['src'];
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

		$key = 'embed' . $url . serialize( $options );
		$cached = $this->_Cache->get( $key );

		if ( $cached !== null ) {
			return $cached;
		}

		try {
			$Media = $this->_embed( $url, $options );
		} catch ( Exception $Exception ) {
			$this->_log( $Exception );
			return null;
		}

		$this->_Cache->set( $key, $Media );
		return $Media;
	}



	/**
	 *	@see Essence::embed( )
	 */

	protected function _embed( $url, array $options ) {

		$providers = $this->_Collection->providers( $url );
		$Media = null;

		foreach ( $providers as $Provider ) {
			try {
				$Media = $Provider->embed( $url, $options );
			} catch ( Exception $Exception ) {
				$this->_log( $Exception );
			}

			if ( $Media !== null ) {
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
	 *
	 *	Thanks to Stefano Zoffoli (https://github.com/stefanozoffoli) for his
	 *	idea (https://github.com/felixgirault/essence/issues/4).
	 *
	 *	@param string $text Text in which to replace URLs.
	 *	@param string $template Replacement template.
	 *	@return string Text with replaced URLs.
	 */

	public function replace( $text, $template = '' ) {

		$Essence = $this;

		return preg_replace_callback(
			// http://daringfireball.net/2009/11/liberal_regex_for_matching_urls
			'#(\s)(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#i',
			function ( $matches ) use ( &$Essence, $template ) {
				$Media = $Essence->embed( $matches[ 2 ]);
				$replacement = '';

				if ( $Media !== null ) {
					if ( empty( $template )) {
						$replacement = $Media->property( 'html' );
					} else {
						$replacements = array( );

						foreach ( $Media as $property => $value ) {
							$replacements["%$property%"] = $value;
						}

						$replacement = str_replace(
							array_keys( $replacements ),
							array_values( $replacements ),
							$template
						);
					}
				}

				return $matches[1] . $replacement;
			},
			$text
		);
	}



	/**
	 *	Returns all errors that occured.
	 *
	 *	@return array All errors.
	 */

	public function errors( ) {

		return $this->_errors;
	}



	/**
	 *	Returns the last error that occured.
	 *
	 *	@return Exception|null The last exception, or null if there
	 *		is no error.
	 */

	public function lastError( ) {

		return empty( $this->_errors )
			? null
			: $this->_errors[ count( $this->_errors ) - 1 ];
	}



	/**
	 *	Logs an exception.
	 *
	 *	@param Exception $Exception The exception to log.
	 */

	protected function _log( Exception $Exception ) {

		$this->_errors[ ] = $Exception;
	}
}
