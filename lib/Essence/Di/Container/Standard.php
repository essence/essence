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
use Essence\Provider\MetaTags;
use Essence\Provider\OEmbed;
use Essence\Provider\OpenGraph;
use Essence\Provider\Preparator\Refactorer;
use Essence\Provider\Presenter\Completer;
use Essence\Provider\Presenter\Reindexer;
use Essence\Provider\Presenter\Youtube;



/**
 *	Contains the default injection properties.
 */

class Standard extends Container {

	/**
	 *	Sets the default properties.
	 *
	 *	@param array $properties Dependency injection settings.
	 */

	public function __construct( array $properties = [ ]) {

		$this->_properties = $properties + [

			/**
			 *	Global helpers.
			 */

			'Http' => Container::unique( function( ) {
				return function_exists( 'curl_init' )
					? new CurlHttpClient( )
					: new NativeHttpClient( );
			}),

			'Dom' => Container::unique( function( ) {
				return new NativeDomParser( );
			}),



			/**
			 *	Completer.
			 */

			'defaults' => [
				'width' => 800,
				'height' => 600
			],

			'Completer' => Container::unique( function( $C ) {
				return new Completer( $C->get( 'defaults' ));
			}),



			/**
			 *	OEmbed.
			 */

			'oEmbedMapping' => [
				'author_name' => 'authorName',
				'author_url' => 'authorUrl',
				'provider_name' => 'providerName',
				'provider_url' => 'providerUrl',
				'cache_age' => 'cacheAge',
				'thumbnail_url' => 'thumbnailUrl',
				'thumbnail_width' => 'thumbnailWidth',
				'thumbnail_height' => 'thumbnailHeight'
			],

			'OEmbedReindexer' => Container::unique( function( $C ) {
				return new Reindexer( $C->get( 'oEmbedMapping' ));
			}),

			'oEmbedPresenters' => Container::unique( function( $C ) {
				return [
					$C->get( 'OEmbedReindexer' ),
					$C->get( 'Completer' )
				];
			}),

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

			'VimeoRefactorer' => Container::unique( function( $C ) {
				return new Refactorer(
					$C->get( 'vimeoId' ),
					$C->get( 'vimeoUrl' )
				);
			}),

			'vimeoPreparators' => Container::unique( function( $C ) {
				return [
					$C->get( 'VimeoRefactorer' )
				];
			}),

			'Vimeo' => function( $C ) {
				return new OEmbed(
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

			'YoutubeRefactorer' => Container::unique( function( $C ) {
				return new Refactorer(
					$C->get( 'youtubeId' ),
					$C->get( 'youtubeUrl' )
				);
			}),

			'youtubePreparators' => Container::unique( function( $C ) {
				return [
					$C->get( 'YoutubeRefactorer' )
				];
			}),

			'youtubeThumbnailFormat' => Youtube::large,

			'YoutubePresenter' => Container::unique( function( $C ) {
				return new Youtube( $C->get( 'youtubeThumbnailFormat' ));
			}),

			'youtubePresenters' => Container::unique( function( $C ) {
				return [
					$C->get( 'YoutubePresenter' )
				];
			}),

			'Youtube' => function( $C ) {
				return new OEmbed(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'youtubePreparators' ),
					$C->get( 'oEmbedPresenters' )
				);
			},



			/**
			 *	OpenGraph.
			 */

			'openGraphScheme' => '#^og:#i',
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

			'OpenGraphReindexer' => Container::unique( function( $C ) {
				return new Reindexer( $C->get( 'openGraphMapping' ));
			}),

			'openGraphPresenters' => Container::unique( function( $C ) {
				return [
					$C->get( 'OpenGraphReindexer' )
				];
			}),

			'OpenGraph' => function( $C ) {
				$OpenGraph = new MetaTags(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					[ ],
					$C->get( 'openGraphPresenters' )
				);

				$OpenGraph->set( 'scheme', $C->get( 'openGraphScheme' ));
				return $OpenGraph;
			},



			/**
			 *	TwitterCards.
			 */

			'twitterCardsScheme' => '#^twitter:#i',
			'twitterCardsMapping' => [
				'twitter:card' => 'type',
				'twitter:title' => 'title',
				'twitter:description' => 'description',
				'twitter:site' => 'providerName',
				'twitter:creator' => 'authorName'
			],

			'TwitterCardsReindexer' => Container::unique( function( $C ) {
				return new Reindexer( $C->get( 'twitterCardsMapping' ));
			}),

			'twitterCardsPresenters' => Container::unique( function( $C ) {
				return [
					$C->get( 'TwitterCardsReindexer' )
				];
			}),

			'TwitterCards' => function( $C ) {
				$TwitterCards = new MetaTags(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					[ ],
					$C->get( 'twitterCardsPresenters' )
				);

				$TwitterCards->set( 'scheme', $C->get( 'twitterCardsScheme' ));
				return $TwitterCards;
			},



			/**
			 *	Providers.
			 */

			'providers' => dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
				. DIRECTORY_SEPARATOR . 'config'
				. DIRECTORY_SEPARATOR . 'providers.json',

			'Collection' => function( $C ) {
				$Collection = new Collection( $C );
				$Collection->load( $C->get( 'providers' ));

				return $Collection;
			}
		];
	}
}
