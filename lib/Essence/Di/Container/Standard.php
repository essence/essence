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
use Essence\Dom\Document\Factory\Native as NativeDomDocument;
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
		$this->_setupHelpers();
		$this->_setupPresenters();
		$this->_setupOEmbed();
		$this->_setupVimeo();
		$this->_setupYoutube();
		$this->_setupOpenGraph();
		$this->_setupTwitterCards();
		$this->_setupCollection();
		$this->_setupEssence();

		$this->configure($properties);
	}



	/**
	 *	Configures global helpers.
	 */
	protected function _setupHelpers() {
		$this->configure([

			//
			'Http.userAgent' => 'Essence',

			//
			'Http' => Container::unique(function() {
				$Http = function_exists('curl_init')
					? new CurlHttpClient()
					: new NativeHttpClient();

				$Http->setUserAgent('Http.userAgent');
				return $Http;
			}),

			//
			'Dom' => Container::unique(function() {
				return new NativeDomDocument();
			})
		]);
	}



	/**
	 *	Configures various presenters.
	 */
	protected function _setupPresenters() {
		$this->configure([

			//
			'Completer.defaults' => [
				'width' => 800,
				'height' => 600
			],

			//
			'Completer' => Container::unique(function($C) {
				return new Completer($C->get('Completer.defaults'));
			})
		]);
	}



	/**
	 *	Configures oEmbed provider.
	 */
	protected function _setupOEmbed() {
		$this->configure([

			//
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

			//
			'OEmbed.Reindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('OEmbed.mapping'));
			}),

			//
			'OEmbed.preparators' => [],

			//
			'OEmbed.presenters' => Container::unique(function($C) {
				return [
					$C->get('OEmbed.Reindexer'),
					$C->get('Completer')
				];
			}),

			//
			'OEmbed' => function($C) {
				$OEmbed = new OEmbed($C->get('Http'), $C->get('Dom'));
				$OEmbed->setPreparators($C->get('OEmbed.preparators'));
				$OEmbed->setPresenters($C->get('OEmbed.presenters'));

				return $OEmbed;
			}
		]);
	}



	/**
	 *	Configures Vimeo provider.
	 */
	protected function _setupVimeo() {
		$this->configure([

			//
			'Vimeo.idPattern' => '~player\.vimeo\.com/video/(?<id>[0-9]+)~i',

			//
			'Vimeo.urlTemplate' => 'http://www.vimeo.com/:id',

			//
			'Vimeo.Refactorer' => Container::unique(function($C) {
				return new Refactorer(
					$C->get('Vimeo.idPattern'),
					$C->get('Vimeo.urlTemplate')
				);
			}),

			//
			'Vimeo.preparators' => Container::unique(function($C) {
				$preparators = $C->get('OEmbed.preparators');
				$preparators[] = $C->get('Vimeo.Refactorer');

				return $preparators;
			}),

			//
			'Vimeo.presenters' => Container::unique(function($C) {
				return $C->get('OEmbed.presenters');
			}),

			//
			'Vimeo' => function($C) {
				$Vimeo = new OEmbed($C->get('Http'), $C->get('Dom'));
				$Vimeo->setPreparators($C->get('Vimeo.preparators'));
				$Vimeo->setPresenters($C->get('Vimeo.presenters'));

				return $Vimeo;
			}
		]);
	}



	/**
	 *	Configures Youtube provider.
	 */
	protected function _setupYoutube() {
		$this->configure([

			//
			'Youtube.idPattern' => '~(?:v=|v/|embed/|youtu\.be/)(?<id>[a-z0-9_-]+)~i',

			//
			'Youtube.urlTemplate' => 'http://www.youtube.com/watch?v=:id',

			//
			'Youtube.thumbnailFormat' => Youtube::large,

			//
			'Youtube.Refactorer' => Container::unique(function($C) {
				return new Refactorer(
					$C->get('Youtube.idPattern'),
					$C->get('Youtube.urlTemplate')
				);
			}),

			//
			'Youtube.preparators' => Container::unique(function($C) {
				$preparators = $C->get('OEmbed.preparators');
				$preparators[] = $C->get('Youtube.Refactorer');

				return $preparators;
			}),

			//
			'Youtube.Presenter' => Container::unique(function($C) {
				return new Youtube($C->get('Youtube.thumbnailFormat'));
			}),

			//
			'Youtube.presenters' => Container::unique(function($C) {
				$presenters = $C->get('OEmbed.presenters');
				$presenters[] = $C->get('Youtube.Presenter');

				return $presenters;
			}),

			//
			'Youtube' => function($C) {
				$Youtube = new OEmbed($C->get('Http'), $C->get('Dom'));
				$Youtube->setPreparators($C->get('Youtube.preparators'));
				$Youtube->setPresenters($C->get('Youtube.presenters'));

				return $Youtube;
			}
		]);
	}



	/**
	 *	Configures the OpenGraph provider.
	 */
	protected function _setupOpenGraph() {
		$this->configure([

			//
			'OpenGraph.metaPattern' => '~^og:~i',

			//
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

			//
			'OpenGraph.preparators' => [],

			//
			'OpenGraph.Reindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('OpenGraph.mapping'));
			}),

			//
			'OpenGraph.presenters' => Container::unique(function($C) {
				return [
					$C->get('OpenGraph.Reindexer')
				];
			}),

			//
			'OpenGraph' => function($C) {
				$OpenGraph = new MetaTags($C->get('Http'), $C->get('Dom'));
				$OpenGraph->setPreparators($C->get('OpenGraph.preparators'));
				$OpenGraph->setPresenters($C->get('OpenGraph.presenters'));
				$OpenGraph->setMetaPattern($C->get('OpenGraph.metaPattern'));

				return $OpenGraph;
			}
		]);
	}



	/**
	 *	Configures the twitter cards provider.
	 */
	protected function _setupTwitterCards() {
		$this->configure([

			//
			'TwitterCards.metaPattern' => '~^twitter:~i',

			//
			'TwitterCards.mapping' => [
				'twitter:card' => 'type',
				'twitter:title' => 'title',
				'twitter:description' => 'description',
				'twitter:site' => 'providerName',
				'twitter:creator' => 'authorName'
			],

			//
			'TwitterCards.preparators' => [],

			//
			'TwitterCards.Reindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('TwitterCards.mapping'));
			}),

			//
			'TwitterCards.presenters' => Container::unique(function($C) {
				return [
					$C->get('TwitterCards.Reindexer')
				];
			}),

			//
			'TwitterCards' => function($C) {
				$TwitterCards = new MetaTags($C->get('Http'), $C->get('Dom'));
				$TwitterCards->setPreparators($C->get('TwitterCards.preparators'));
				$TwitterCards->setPresenters($C->get('TwitterCards.presenters'));
				$TwitterCards->setMetaPattern($C->get('TwitterCards.metaPattern'));

				return $TwitterCards;
			}
		]);
	}



	/**
	 *	Configures the providers collection.
	 */
	protected function _setupCollection() {
		$this->configure([

			//
			'Collection.providers' => dirname(dirname(dirname(dirname(dirname(__FILE__)))))
				. DIRECTORY_SEPARATOR . 'config'
				. DIRECTORY_SEPARATOR . 'providers.json',

			//
			'Collection' => Container::unique(function($C) {
				$Collection = new Collection($C);
				$Collection->load($C->get('Collection.providers'));

				return $Collection;
			})
		]);
	}



	/**
	 *	Configures Essence's helpers.
	 */
	protected function _setupEssence() {
		$this->configure([

			//
			'Crawler' => Container::unique(function($C) {
				return new Crawler(
					$C->get('Collection'),
					$C->get('Dom')
				);
			}),

			//
			'Extractor' => Container::unique(function($C) {
				return new Extractor(
					$C->get('Collection')
				);
			}),

			//
			'Replacer.urlPattern' =>
				'~
					(?<!=["\']) # avoids matching URLs in HTML attributes
					(?:https?:)//
					(?:www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)?
					(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+
					(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'"\.,<>?«»“”‘’])
				~ix',

			//
			'Replacer' => Container::unique(function($C) {
				$Replacer = new Replacer($C->get('Extractor'));
				$Replacer->setUrlPattern($C->get('Replacer.urlPattern'));

				return $Replacer;
			})
		]);
	}
}
