<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Di\Container;

use Essence\Essence;
use Essence\Di\Container;
use Essence\Dom\Parser\Native as NativeDomParser;
use Essence\Http\Client\Curl as CurlHttpClient;
use Essence\Http\Client\Native as NativeHttpClient;
use Essence\Provider\Collection;
use Essence\Provider\OEmbed;
use Essence\Provider\OEmbed\Vimeo;
use Essence\Provider\OEmbed\Youtube;
use Essence\Provider\OpenGraph;
use Essence\Media\Preparator;
use Essence\Media\Preparator\Bandcamp as BandcampPreparator;
use Essence\Media\Preparator\Vine as VinePreparator;
use Essence\Media\Preparator\Youtube as YoutubePreparator;
use Essence\Filter\Preparator\Refactorer;
use Essence\Filter\Presenter\Completer;
use Essence\Filter\Presenter\Reindexer;
use Essence\Filter\Presenter\Templater;



/**
 *	Contains the default injection properties.
 */

class Standard extends Container {

	/**
	 *	Sets the default properties.
	 */

	public function __construct( array $properties = [ ]) {

		$this->_properties = $properties + [

			// A cURL HTTP client is shared across the application
			// If cURL isn't available, a native client is used
			'Http' => Container::unique( function( ) {
				return function_exists( 'curl_init' )
					? new CurlHttpClient( )
					: new NativeHttpClient( );
			}),

			// A native DOM parser is shared across the application
			'Dom' => Container::unique( function( ) {
				return new NativeDomParser( );
			}),



			/**
			 *	Completer.
			 */

			//
			'defaults' => [
				'width' => 800,
				'height' => 600
			],

			//
			'Completer' => Container::unique( function( $C ) {
				return new Completer( $C->get( 'defaults' ));
			}),



			/**
			 *	OEmbed.
			 */

			//
			'oEmbedMapping' => [
				'og:type' => 'type',
				'og:title' => 'title',
				'og:description' => 'description',
				'og:site_name' => 'providerName',
				'og:image' => 'thumbnailUrl',
				'og:image:url' => 'thumbnailUrl',
				'og:image:width' => 'width',
				'og:image:height' => 'height',
				'og:video:width' => 'width',
				'og:video:height' => 'height',
				'og:url' => 'url'
			],

			//
			'OEmbedReindexer' => Container::unique( function( $C ) {
				return new Reindexer( $C->get( 'oEmbedMapping' ));
			}),

			//
			'oEmbedPresenters' => Container::unique( function( $C ) {
				return [
					$C->get( 'OEmbedReindexer' ),
					$C->get( 'Completer' )
				];
			}),

			// The OEmbed provider uses the shared HTTP client and DOM parser.
			'OEmbed' => function( $C ) {
				return new OEmbed(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					[ ],
					$C->get( 'oEmbedPresenters' )
				);
			},

			/**
			 *	Vimeo.
			 */

			'vimeoId' => '#player\.vimeo\.com/video/(?<id>[0-9]+)#i',
			'vimeoUrl' => 'http://www.vimeo.com/:id',

			//
			'VimeoRefactorer' => Container::unique( function( $C ) {
				return new Refactorer(
					$C->get( 'vimeoId' ),
					$C->get( 'vimeoUrl' )
				);
			}),

			//
			'vimeoPreparators' => Container::unique( function( $C ) {
				return [
					$C->get( 'VimeoRefactorer' )
				];
			}),

			// The Vimeo provider uses the shared HTTP client and DOM parser.
			'Vimeo' => function( $C ) {
				return new Vimeo(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'vimeoPreparators' ),
					$C->get( 'oEmbedPresenters' )
				);
			},



			/**
			 *	Youtube.
			 */

			'youtubeId' => '#(?:v=|v/|embed/|youtu\.be/)(?<id>[a-z0-9_-]+)#i',
			'youtubeUrl' => 'http://www.youtube.com/watch?v=:id',

			//
			'YoutubeRefactorer' => Container::unique( function( $C ) {
				return new Refactorer(
					$C->get( 'youtubeId' ),
					$C->get( 'youtubeUrl' )
				);
			}),

			//
			'youtubePreparators' => Container::unique( function( $C ) {
				return [
					$C->get( 'YoutubeRefactorer' )
				];
			}),

			// The Youtube provider uses the shared HTTP client and DOM parser.
			'Youtube' => function( $C ) {
				return new Youtube(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'youtubePreparators' ),
					$C->get( 'oEmbedPresenters' )
				);
			},



			/**
			 *	OpenGraph.
			 */

			//
			'openGraphMapping' => [
				'og:type' => 'type',
				'og:title' => 'title',
				'og:description' => 'description',
				'og:site_name' => 'providerName',
				'og:image' => 'thumbnailUrl',
				'og:image:url' => 'thumbnailUrl',
				'og:image:width' => 'width',
				'og:image:height' => 'height',
				'og:video:width' => 'width',
				'og:video:height' => 'height',
				'og:url' => 'url'
			],

			//
			'OpenGraphReindexer' => Container::unique( function( $C ) {
				return new Reindexer( $C->get( 'openGraphMapping' ));
			}),

			//
			'openGraphPresenters' => Container::unique( function( $C ) {
				return [
					$C->get( 'OpenGraphReindexer' )
				];
			}),

			// The OpenGraph provider uses the shared HTTP client and DOM parser.
			'OpenGraph' => function( $C ) {
				return new OpenGraph(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					[ ],
					$C->get( 'openGraphPresenters' )
				);
			},



			/**
			 *	Bandcamp.
			 */

			// The Bandcamp provider uses the shared HTTP client and DOM parser.
			'Bandcamp' => function( $C ) {
				return $C->get( 'OpenGraph' );
			},



			/**
			 *	Vine.
			 */

			// The Vine provider uses the shared HTTP client and DOM parser.
			'Vine' => function( $C ) {
				return $C->get( 'OpenGraph' );
			},



			/**
			 *
			 */

			// Providers are loaded from the default config file
			'providers' => dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
				. DIRECTORY_SEPARATOR . 'config'
				. DIRECTORY_SEPARATOR . 'providers.json',

			// The provider collection uses the container
			'Collection' => function( $C ) {
				$Collection = new Collection( $C );
				$Collection->load( $C->get( 'providers' ));

				return $Collection;
			},

			// Essence uses the provider collection, and the shared HTTP client
			// and DOM parser.
			'Essence' => function( $C ) {
				return new Essence(
					$C->get( 'Collection' ),
					$C->get( 'Http' ),
					$C->get( 'Dom' )
				);
			}
		];
	}
}
