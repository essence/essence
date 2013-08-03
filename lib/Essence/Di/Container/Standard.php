<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Di\Container;

use Essence\Essence;
use Essence\Di\Container;
use Essence\Cache\Engine\Volatile as VolatileCacheEngine;
use Essence\Dom\Parser\Native as NativeDomParser;
use Essence\Http\Client\Curl as CurlHttpClient;
use Essence\Provider\Collection;
use Essence\Provider\OEmbed;
use Essence\Provider\OpenGraph;



/**
 *	Contains the default injection properties.
 *
 *	@package fg.Essence.Di.Container
 */

class Standard extends Container {

	/**
	 *	Sets the default properties.
	 */

	public function __construct( ) {

		$this->_properties = array(

			// providers are loaded from the default config file
			'providers' => ESSENCE_DEFAULT_PROVIDERS,

			// A volatile cache engine is shared across the application
			'Cache' => Container::unique( function( ) {
				return new VolatileCacheEngine( );
			}),

			// A cURL HTTP client is shared across the application
			'Http' => Container::unique( function( ) {
				return new CurlHttpClient( );
			}),

			// A native DOM parser is shared across the application
			'Dom' => Container::unique( function( ) {
				return new NativeDomParser( );
			}),

			// A null logger is shared across the application
			'Log' => null,

			// The OEmbed provider uses the shared HTTP client, DOM parser
			// and logger.
			'OEmbed' => function( $C ) {
				return new OEmbed(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'Log' )
				);
			},

			// The OpenGraph provider uses the shared HTTP client, DOM parser
			// and logger.
			'OpenGraph' => function( $C ) {
				return new OpenGraph(
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'Log' )
				);
			},

			// The provider collection uses the container
			'Collection' => function( $C ) {
				$Collection = new Collection( $C );
				$Collection->load( $C->get( 'providers' ));

				return $Collection;
			},

			// Essence uses the provider collection, and the shared cache engine,
			// HTTP client and DOM parser.
			'Essence' => function( $C ) {
				return new Essence(
					$C->get( 'Collection' ),
					$C->get( 'Cache' ),
					$C->get( 'Http' ),
					$C->get( 'Dom' ),
					$C->get( 'Log' )
				);
			}
		);
	}
}
