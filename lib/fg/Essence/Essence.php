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

		$this->_Collection = new ProviderCollection( $providers );
	}



	/**
	 *	If the url can be parsed directly by one of the registered providers,
	 *	it is returned as is. Otherwise, the page is parsed to find such urls.
	 *
	 *	@param string $url The Url to extract.
	 *	@return array An array of extracted URLs.
	 */

	public function extract( $url ) {

		// if a provider can directly handle the url, there is no more work to do.

		if ( $this->_Collection->hasProvider( $url )) {
			return array( $url );
		}

		try {
			$urls = $this->_extractUrls( $url );
		} catch ( Exception $Exception ) {
			$this->_log( $Exception );
			return array( );
		}

		$fetchable = array( );

		foreach ( $urls as $url ) {
			if ( $this->_Collection->hasProvider( $url )) {
				$fetchable[ ] = $url;
			}
		}

		return array_values( array_unique( $fetchable )); // array_values reindexes the array
	}



	/**
	 *	Extracts URLs from a web page.
	 *
	 *	@param string $url The web page to extract URLs from.
	 *	@return array Extracted URLs.
	 */

	protected function _extractUrls( $url ) {

		$attributes = Html::extractAttributes(
			Http::get( $url ),
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

		$providers = $this->_Collection->providers( $url );
		$Media = null;

		foreach ( $providers as $Provider ) {
			try {
				$Media = $Provider->embed( $url, $options );
			} catch ( Exception $Exception ) {
				$this->_log( $Exception );
			}

			if ( $Media !== null ) {
				return $Media;
			}
		}

		return null;
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

		$count = preg_match_all(
			// http://daringfireball.net/2009/11/liberal_regex_for_matching_urls
			'#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#i',
			$text,
			$matches,
			PREG_PATTERN_ORDER
		);

		if ( $count ) {
			$medias = $this->embedAll( $matches[ 0 ]);
			$replacements = array( );

			foreach ( $medias as $url => $Media ) {
				if ( $Media !== null ) {
					$replacements[ $url ] = empty( $template )
						? $Media->html
						: $this->_renderTemplate( $template, $Media );
				}
			}

			$text = str_replace(
				array_keys( $replacements ),
				array_values( $replacements ),
				$text
			);
		}

		return $text;
	}



	/**
	 *	Renders the given template with the media properties.
	 *
	 *	@param string $template Template to render.
	 *	@param Media $Media Media object from which to gather properties.
	 *	@return string Rendered template.
	 */

	protected function _renderTemplate( $template, Media $Media ) {

		$replacements = array( );

		foreach ( $Media as $property => $value ) {
			$replacements["%$property%"] = $value;
		}

		return str_replace(
			array_keys( $replacements ),
			array_values( $replacements ),
			$template
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
