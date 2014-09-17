<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Di\Container;

use Essence\Essence;
use Essence\Crawler;
use Essence\Extractor;
use Essence\Replacer;
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
	public function __construct(array $properties = []) {
		$this->_properties = $properties + [

			/**
			 *	Global helpers.
			 */
			'Http.userAgent' => 'Essence',

			'Http' => Container::unique(function() {
				$Http = function_exists('curl_init')
					? new CurlHttpClient()
					: new NativeHttpClient();

				$Http->setUserAgent('Http.userAgent');
				return $Http;
			}),

			'Dom' => Container::unique(function() {
				return new NativeDomParser();
			}),



			/**
			 *	Completer.
			 */
			'Completer.defaults' => [
				'width' => 800,
				'height' => 600
			],

			'Completer' => Container::unique(function($C) {
				return new Completer($C->get('Completer.defaults'));
			}),



			/**
			 *	OEmbed.
			 */
			'OEmbed.mapping' => [
				'author_name' => 'authorName',
				'author_url' => 'authorUrl',
				'provider_name' => 'providerName',
				'provider_url' => 'providerUrl',
				'cache_age' => 'cacheAge',
				'thumbnail_url' => 'thumbnailUrl',
				'thumbnail_width' => 'thumbnailWidth',
				'thumbnail_height' => 'thumbnailHeight'
			],

			'OEmbed.Reindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('OEmbed.mapping'));
			}),

			'OEmbed.presenters' => Container::unique(function($C) {
				return [
					$C->get('OEmbed.Reindexer'),
					$C->get('Completer')
				];
			}),

			'OEmbed' => function($C) {
				return new OEmbed(
					$C->get('Http'),
					$C->get('Dom'),
					[],
					$C->get('OEmbed.presenters')
				);
			},



			/**
			 *	Vimeo.
			 */
			'Vimeo.id' => '#player\.vimeo\.com/video/(?<id>[0-9]+)#i',
			'Vimeo.url' => 'http://www.vimeo.com/:id',

			'Vimeo.Refactorer' => Container::unique(function($C) {
				return new Refactorer(
					$C->get('Vimeo.id'),
					$C->get('Vimeo.url')
				);
			}),

			'Vimeo.preparators' => Container::unique(function($C) {
				return [
					$C->get('Vimeo.Refactorer')
				];
			}),

			'Vimeo' => function($C) {
				return new OEmbed(
					$C->get('Http'),
					$C->get('Dom'),
					$C->get('Vimeo.preparators'),
					$C->get('OEmbed.presenters')
				);
			},



			/**
			 *	Youtube.
			 */
			'Youtube.Id' => '#(?:v=|v/|embed/|youtu\.be/)(?<id>[a-z0-9_-]+)#i',
			'Youtube.url' => 'http://www.youtube.com/watch?v=:id',

			'Youtube.Refactorer' => Container::unique(function($C) {
				return new Refactorer(
					$C->get('Youtube.Id'),
					$C->get('Youtube.url')
				);
			}),

			'Youtube.preparators' => Container::unique(function($C) {
				return [
					$C->get('Youtube.Refactorer')
				];
			}),

			'Youtube.thumbnailFormat' => Youtube::large,

			'Youtube.Presenter' => Container::unique(function($C) {
				return new Youtube($C->get('Youtube.thumbnailFormat'));
			}),

			'Youtube.presenters' => Container::unique(function($C) {
				return [
					$C->get('Youtube.Presenter')
				];
			}),

			'Youtube' => function($C) {
				return new OEmbed(
					$C->get('Http'),
					$C->get('Dom'),
					$C->get('Youtube.preparators'),
					$C->get('OEmbed.presenters')
				);
			},



			/**
			 *	OpenGraph.
			 */
			'OpenGraph.metaPattern' => '~^og:~i',
			'OpenGraph.mapping' => [
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

			'OpenGraph.Reindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('OpenGraph.mapping'));
			}),

			'OpenGraph.presenters' => Container::unique(function($C) {
				return [
					$C->get('OpenGraph.Reindexer')
				];
			}),

			'OpenGraph' => function($C) {
				$OpenGraph = new MetaTags(
					$C->get('Http'),
					$C->get('Dom'),
					[],
					$C->get('OpenGraph.presenters')
				);

				$OpenGraph->setMetaPattern($C->get('OpenGraph.metaPattern'));
				return $OpenGraph;
			},



			/**
			 *	TwitterCards.
			 */
			'TwitterCards.metaPattern' => '~^twitter:~i',
			'TwitterCards.mapping' => [
				'twitter:card' => 'type',
				'twitter:title' => 'title',
				'twitter:description' => 'description',
				'twitter:site' => 'providerName',
				'twitter:creator' => 'authorName'
			],

			'TwitterCards.Reindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('TwitterCards.mapping'));
			}),

			'TwitterCards.presenters' => Container::unique(function($C) {
				return [
					$C->get('TwitterCards.Reindexer')
				];
			}),

			'TwitterCards' => function($C) {
				$TwitterCards = new MetaTags(
					$C->get('Http'),
					$C->get('Dom'),
					[],
					$C->get('TwitterCards.presenters')
				);

				$TwitterCards->setMetaPattern($C->get('TwitterCards.metaPattern'));
				return $TwitterCards;
			},



			/**
			 *	Providers.
			 */
			'Collection.providers' => dirname(dirname(dirname(dirname(dirname(__FILE__)))))
				. DIRECTORY_SEPARATOR . 'config'
				. DIRECTORY_SEPARATOR . 'providers.json',

			'Collection' => Container::unique(function($C) {
				$Collection = new Collection($C);
				$Collection->load($C->get('Collection.providers'));

				return $Collection;
			}),



			/**
			 *
			 */
			'Crawler' => Container::unique(function($C) {
				return new Crawler(
					$C->get('Collection'),
					$C->get('Http'),
					$C->get('Dom')
				);
			}),

			'Extractor' => Container::unique(function($C) {
				return new Extractor(
					$C->get('Collection')
				);
			}),

			'Replacer.urlPattern' =>
				'~
					(?<!=["\']) # avoids matching URLs in HTML attributes
					(?:https?:)//
					(?:www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)?
					(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+
					(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'"\.,<>?«»“”‘’])
				~ix',

			'Replacer' => Container::unique(function($C) {
				$Replacer = new Replacer($C->get('Extractor'));
				$Replacer->setUrlPattern($C->get('Replacer.urlPattern'));

				return $Replacer;
			})
		];
	}
}
