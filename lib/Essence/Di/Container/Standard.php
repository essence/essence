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



/**
 *	Contains the default injection properties.
 */

class Standard extends Container {

	/**
	 *	Sets the default properties.
	 */

	public function __construct( array $properties = [ ]) {

		$this->_properties = $properties + [

			// Providers are loaded from the default config file
			'providers' => dirname( dirname( dirname( dirname( dirname( __FILE__ )))))
				. DIRECTORY_SEPARATOR . 'config'
				. DIRECTORY_SEPARATOR . 'providers.json',

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

			//
			'Preparator' => Container::unique( function( ) {
				return new Preparator( );
			}),

			// The OEmbed provider uses the shared HTTP client and DOM parser.
			'OEmbed' => function( $C ) {
				return new OEmbed(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'Preparator' )
				);
			},

			// The Vimeo provider uses the shared HTTP client and DOM parser.
			'Vimeo' => function( $C ) {
				return new Vimeo(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'Preparator' )
				);
			},

			//
			'YoutubePreparator' => Container::unique( function( ) {
				return new YoutubePreparator( );
			}),

			// The Youtube provider uses the shared HTTP client and DOM parser.
			'Youtube' => function( $C ) {
				return new Youtube(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'Preparator' )
				);
			},

			// The OpenGraph provider uses the shared HTTP client and DOM parser.
			'OpenGraph' => function( $C ) {
				return new OpenGraph(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'Preparator' )
				);
			},

			//
			'BandcampPreparator' => Container::unique( function( ) {
				return new BandcampPreparator( );
			}),

			// The Bandcamp provider uses the shared HTTP client and DOM parser.
			'Bandcamp' => function( $C ) {
				return new OpenGraph(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'BandcampPreparator' )
				);
			},

			//
			'VinePreparator' => Container::unique( function( ) {
				return new VinePreparator( );
			}),

			// The Vine provider uses the shared HTTP client and DOM parser.
			'Vine' => function( $C ) {
				return new OpenGraph(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'VinePreparator' )
				);
			},

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
