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
		$this->_setupProviders();
		$this->_setupFilters();

		$this->configure($properties);
	}



	/**
	 *	Configures global helpers.
	 */
	protected function _setupHelpers() {
		$this->configure([
			'httpUserAgent' => 'Essence',
			'Http' => Container::unique(function() {
				$Http = function_exists('curl_init')
					? new CurlHttpClient()
					: new NativeHttpClient();

				$Http->setUserAgent('httpUserAgent');
				return $Http;
			}),
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
			'completerDefaults' => [
				'width' => 800,
				'height' => 600
			],
			'Completer' => Container::unique(function($C) {
				return new Completer($C->get('completerDefaults'));
			})
		]);
	}



	/**
	 *	Configures oEmbed provider.
	 */
	protected function _setupOEmbed() {
		$this->configure([
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
			'oEmbedPreparators' => [],
			'oEmbedPresenters' => Container::unique(function($C) {
				return [
					$C->get('OEmbedReindexer'),
					$C->get('Completer')
				];
			}),
			'OEmbedReindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('oEmbedMapping'));
			}),
			'OEmbedProvider' => function($C) {
				$OEmbed = new OEmbed($C->get('Http'), $C->get('Dom'));
				$OEmbed->setPreparators($C->get('oEmbedPreparators'));
				$OEmbed->setPresenters($C->get('oEmbedPresenters'));

				return $OEmbed;
			}
		]);
	}



	/**
	 *	Configures Vimeo provider.
	 */
	protected function _setupVimeo() {
		$this->configure([
			'vimeoIdPattern' => '~player\.vimeo\.com/video/(?<id>[0-9]+)~i',
			'vimeoUrlTemplate' => 'http://www.vimeo.com/:id',
			'vimeoPreparators' => Container::unique(function($C) {
				$preparators = $C->get('oEmbedPreparators');
				$preparators[] = $C->get('VimeoRefactorer');

				return $preparators;
			}),
			'vimeoPresenters' => Container::unique(function($C) {
				return $C->get('oEmbedPresenters');
			}),
			'VimeoRefactorer' => Container::unique(function($C) {
				return new Refactorer(
					$C->get('vimeoIdPattern'),
					$C->get('vimeoUrlTemplate')
				);
			}),
			'VimeoProvider' => function($C) {
				$Vimeo = new OEmbed($C->get('Http'), $C->get('Dom'));
				$Vimeo->setPreparators($C->get('vimeoPreparators'));
				$Vimeo->setPresenters($C->get('vimeoPresenters'));

				return $Vimeo;
			}
		]);
	}



	/**
	 *	Configures Youtube provider.
	 */
	protected function _setupYoutube() {
		$this->configure([
			'youtubeIdPattern' => '~(?:v=|v/|embed/|youtu\.be/)(?<id>[a-z0-9_-]+)~i',
			'youtubeUrlTemplate' => 'http://www.youtube.com/watch?v=:id',
			'youtubeThumbnailFormat' => Youtube::large,
			'youtubePreparators' => Container::unique(function($C) {
				$preparators = $C->get('oEmbedPreparators');
				$preparators[] = $C->get('YoutubeRefactorer');

				return $preparators;
			}),
			'youtubePresenters' => Container::unique(function($C) {
				$presenters = $C->get('oEmbedPresenters');
				$presenters[] = $C->get('YoutubePresenter');

				return $presenters;
			}),
			'YoutubeRefactorer' => Container::unique(function($C) {
				return new Refactorer(
					$C->get('youtubeIdPattern'),
					$C->get('youtubeUrlTemplate')
				);
			}),
			'YoutubePresenter' => Container::unique(function($C) {
				return new Youtube($C->get('youtubeThumbnailFormat'));
			}),
			'YoutubeProvider' => function($C) {
				$Youtube = new OEmbed($C->get('Http'), $C->get('Dom'));
				$Youtube->setPreparators($C->get('youtubePreparators'));
				$Youtube->setPresenters($C->get('youtubePresenters'));

				return $Youtube;
			}
		]);
	}



	/**
	 *	Configures the OpenGraph provider.
	 */
	protected function _setupOpenGraph() {
		$this->configure([
			'openGraphMetaPattern' => '~^og:~i',
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
			'openGraphPreparators' => [],
			'openGraphPresenters' => Container::unique(function($C) {
				return [
					$C->get('OpenGraphReindexer')
				];
			}),
			'OpenGraphReindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('openGraphMapping'));
			}),
			'OpenGraphProvider' => function($C) {
				$OpenGraph = new MetaTags($C->get('Http'), $C->get('Dom'));
				$OpenGraph->setPreparators($C->get('openGraphPreparators'));
				$OpenGraph->setPresenters($C->get('openGraphPresenters'));
				$OpenGraph->setMetaPattern($C->get('openGraphMetaPattern'));

				return $OpenGraph;
			}
		]);
	}



	/**
	 *	Configures the twitter cards provider.
	 */
	protected function _setupTwitterCards() {
		$this->configure([
			'twitterCardsMetaPattern' => '~^twitter:~i',
			'twitterCardsMapping' => [
				'twitter:card' => 'type',
				'twitter:title' => 'title',
				'twitter:description' => 'description',
				'twitter:site' => 'providerName',
				'twitter:creator' => 'authorName'
			],
			'twitterCardsPreparators' => [],
			'twitterCardsPresenters' => Container::unique(function($C) {
				return [
					$C->get('TwitterCardsReindexer')
				];
			}),
			'TwitterCardsReindexer' => Container::unique(function($C) {
				return new Reindexer($C->get('twitterCardsMapping'));
			}),
			'TwitterCardsProvider' => function($C) {
				$TwitterCards = new MetaTags($C->get('Http'), $C->get('Dom'));
				$TwitterCards->setPreparators($C->get('twitterCardsPreparators'));
				$TwitterCards->setPresenters($C->get('twitterCardsPresenters'));
				$TwitterCards->setMetaPattern($C->get('twitterCardsMetaPattern'));

				return $TwitterCards;
			}
		]);
	}



	/**
	 *	Configures the providers collection.
	 */
	protected function _setupCollection() {
		$this->configure([
			'Collection' => Container::unique(function($C) {
				return new Collection($C);
			})
		]);
	}



	/**
	 *	Configures Essence's helpers.
	 */
	protected function _setupEssence() {
		$this->configure([
			'replacerUrlPattern' =>
				'~
					(?<!=["\']) # avoids matching URLs in HTML attributes
					(?:https?:)//
					(?:www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)?
					(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+
					(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'"\.,<>?«»“”‘’])
				~ix',

			'Replacer' => Container::unique(function($C) {
				$Replacer = new Replacer($C->get('Extractor'));
				$Replacer->setUrlPattern($C->get('replacerUrlPattern'));

				return $Replacer;
			}),
			'Crawler' => Container::unique(function($C) {
				return new Crawler(
					$C->get('Collection'),
					$C->get('Dom')
				);
			}),
			'Extractor' => Container::unique(function($C) {
				return new Extractor(
					$C->get('Collection')
				);
			})
		]);
	}



	/**
	 *	Configures default providers.
	 */
	protected function _setupProviders() {
		$this->configure([
			'23hq' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.23hq.com/23/oembed?format=json&url=:url'
				);
			}),
			'Animoto' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://animoto.com/oembeds/create?format=json&url=:url'
				);
			}),
			'Aol' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://on.aol.com/api?format=json&url=:url'
				);
			}),
			'App.net' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://alpha-api.app.net/oembed?format=json&url=:url'
				);
			}),
			'Bambuser' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://api.bambuser.com/oembed.json?url=:url'
				);
			}),
			'Bandcamp' => Container::unique(function($C) {
				return $C->get('TwitterCardsProvider');
			}),
			'Blip.tv' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://blip.tv/oembed?format=json&url=:url'
				);
			}),
			'Cacoo' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://cacoo.com/oembed.json?url=:url'
				);
			}),
			'CanalPlus' => Container::unique(function($C) {
				return $C->get('OpenGraphProvider');
			}),
			'Chirb.it' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://chirb.it/oembed.json?url=:url'
				);
			}),
			'CircuitLab' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://www.circuitlab.com/circuit/oembed?format=json&url=:url'
				);
			}),
			'Clikthrough' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://clikthrough.com/services/oembed?format=json&url=:url'
				);
			}),
			'CollegeHumorOEmbed' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.collegehumor.com/oembed.json?url=:url'
				);
			}),
			'CollegeHumorOpenGraph' => Container::unique(function($C) {
				return $C->get('OpenGraphProvider');
			}),
			'Coub' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://coub.com/api/oembed.json?url=:url'
				);
			}),
			'CrowdRanking' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://crowdranking.com/api/oembed.json?url=:url'
				);
			}),
			'DailyMile' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://api.dailymile.com/oembed?format=json&url=:url'
				);
			}),
			'Dailymotion' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.dailymotion.com/services/oembed?format=json&url=:url'
				);
			}),
			'Deviantart' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://backend.deviantart.com/oembed?format=json&url=:url'
				);
			}),
			'Dipity' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.dipity.com/oembed/timeline?format=json&url=:url'
				);
			}),
			'Dotsub' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://dotsub.com/services/oembed?format=json&url=:url'
				);
			}),
			'EdocrOEmbed' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.edocr.com/api/oembed?format=json&url=:url'
				);
			}),
			'EdocrTwitterCards' => Container::unique(function($C) {
				return $C->get('TwitterCardsProvider');
			}),
			'Flickr' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://flickr.com/services/oembed?format=json&url=:url'
				);
			}),
			'FunnyOrDie' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.funnyordie.com/oembed?format=json&url=:url'
				);
			}),
			'Gist' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://github.com/api/oembed?format=json&url=:url'
				);
			}),
			'Gmep' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://gmep.org/oembed.json?url=:url'
				);
			}),
			'HowCast' => Container::unique(function($C) {
				return $C->get('OpenGraphProvider');
			}),
			'Huffduffer' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://huffduffer.com/oembed?format=json&url=:url'
				);
			}),
			'Hulu' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.hulu.com/api/oembed.json?url=:url'
				);
			}),
			'Ifixit' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.ifixit.com/Embed?format=json&url=:url'
				);
			}),
			'Ifttt' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.ifttt.com/oembed?format=json&url=:url'
				);
			}),
			'Imgur' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://api.imgur.com/oembed?format=json&url=:url'
				);
			}),
			'InstagramOEmbed' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://api.instagram.com/oembed?format=json&url=:url'
				);
			}),
			'InstagramOpenGraph' => Container::unique(function($C) {
				return $C->get('OpenGraphProvider');
			}),
			'Jest' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.jest.com/oembed.json?url=:url'
				);
			}),
			'Justin.tv' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://api.justin.tv/api/embed/from_url.json?url=:url'
				);
			}),
			'Kickstarter' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.kickstarter.com/services/oembed?format=json&url=:url'
				);
			}),
			'Meetup' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://api.meetup.com/oembed?format=json&url=:url'
				);
			}),
			'Mixcloud' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.mixcloud.com/oembed?format=json&url=:url'
				);
			}),
			'Mobypicture' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://api.mobypicture.com/oEmbed?format=json&url=:url'
				);
			}),
			'Nfb' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.nfb.ca/remote/services/oembed?format=json&url=:url'
				);
			}),
			'Official.fm' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://official.fm/services/oembed?format=json&url=:url'
				);
			}),
			'Polldaddy' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://polldaddy.com/oembed?format=json&url=:url'
				);
			}),
			'PollEverywhere' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.polleverywhere.com/services/oembed?format=json&url=:url'
				);
			}),
			'Prezi' => Container::unique(function($C) {
				return $C->get('OpenGraphProvider');
			}),
			'Qik' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://qik.com/api/oembed.json?url=:url'
				);
			}),
			'Rdio' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.rdio.com/api/oembed?format=json&url=:url'
				);
			}),
			'Revision3' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://revision3.com/api/oembed?format=json&url=:url'
				);
			}),
			'Roomshare' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://roomshare.jp/en/oembed.json?&url=:url'
				);
			}),
			'Sapo' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://videos.sapo.pt/oembed?format=json&url=:url'
				);
			}),
			'Screenr' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.screenr.com/api/oembed.json?url=:url'
				);
			}),
			'Scribd' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.scribd.com/services/oembed?format=json&url=:url'
				);
			}),
			'Shoudio' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://shoudio.com/api/oembed?format=json&url=:url'
				);
			}),
			'Sketchfab' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://sketchfab.com/oembed?format=json&url=:url'
				);
			}),
			'SlideShare' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.slideshare.net/api/oembed/2?format=json&url=:url'
				);
			}),
			'SoundCloud' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://soundcloud.com/oembed?format=json&url=:url'
				);
			}),
			'SpeakerDeck' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://speakerdeck.com/oembed.json?url=:url'
				);
			}),
			'Spotify' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://embed.spotify.com/oembed?format=json&url=:url'
				);
			}),
			'TedOEmbed' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.ted.com/talks/oembed.json?url=:url'
				);
			}),
			'TedOpenGraph' => Container::unique(function($C) {
				return $C->get('OpenGraphProvider');
			}),
			'Twitter' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'https://api.twitter.com/1/statuses/oembed.json?url=:url'
				);
			}),
			'Ustream' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.ustream.tv/oembed?format=json&url=:url'
				);
			}),
			'Vhx' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://vhx.tv/services/oembed.json?url=:url'
				);
			}),
			'Viddler' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.viddler.com/oembed/?url=:url'
				);
			}),
			'Videojug' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.videojug.com/oembed.json?url=:url'
				);
			}),
			'Vimeo' => Container::unique(function($C) {
				return $C->get('VimeoProvider')->setEndpoint(
					'http://vimeo.com/api/oembed.json?url=:url'
				);
			}),
			'Vine' => Container::unique(function($C) {
				return $C->get('TwitterCardsProvider');
			}),
			'Wistia' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://fast.wistia.com/oembed?format=json&url=:url'
				);
			}),
			'WordPress' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://public-api.wordpress.com/oembed/1.0?format=json&for=me&url=:url'
				);
			}),
			'Yfrog' => Container::unique(function($C) {
				return $C->get('OEmbedProvider')->setEndpoint(
					'http://www.yfrog.com/api/oembed?format=json&url=:url'
				);
			}),
			'Youtube' => Container::unique(function($C) {
				return $C->get('YoutubeProvider')->setEndpoint(
					'http://www.youtube.com/oembed?format=json&url=:url'
				);
			})
		]);
	}



	/**
	 *	Configures filters associating URLs to providers.
	 */
	protected function _setupFilters() {
		$this->set('filters', [
			'23hq' => '~23hq\.com/.+/photo/.+~i',
			'Animoto' => '~animoto\.com/play/.+~i',
			'Aol' => '~on\.aol\.com/video/.+~i',
			'App.net' => '~(alpha|photo)\.app\.net/.+(/post)?/.+~i',
			'Bambuser' => '~bambuser\.com/(v|channel)/.+~i',
			'Bandcamp' => '~^https?://(?:[^\.]+\.)?bandcamp\.com/(album|track)/~i',
			'Blip.tv' => '~blip\.tv/.+~i',
			'Cacoo' => '~cacoo\.com/.+~i',
			'CanalPlus' => '~canalplus\.fr~i',
			'Chirb.it' => '~chirb\.it/.+~i',
			'CircuitLab' => '~circuitlab\.com/circuit/.+~i',
			'Clikthrough' => '~clikthrough\.com/theater/video/\d+~i',
			'CollegeHumorOEmbed' => '~collegehumor\.com/(video|embed)/.+~i',
			'CollegeHumorOpenGraph' => '~collegehumor\.com/post/.+~i',
			'Coub' => '~coub\.com/(view|embed)/.+~i',
			'CrowdRanking' => '~crowdranking\.com/.+/.+~i',
			'DailyMile' => '~dailymile\.com/people/.+/entries/.+~i',
			'Dailymotion' => '~(dailymotion\.com\/video|dai\.ly)\/[a-z0-9]~i',
			'Deviantart' => '~deviantart\.com/.+~i',
			'Dipity' => '~dipity\.com/.+~i',
			'Dotsub' => '~dotsub\.com/view/.+~i',
			'EdocrOEmbed' => '~edocr\.com/doc/[0-9]+/.+~i',
			'EdocrTwitterCards' => '~edocr\.com/doc/[0-9]+/.+~i',
			'Flickr' => '~flickr\.com/photos/[a-zA-Z0-9@\._]+/[0-9]+~i',
			'FunnyOrDie' => '~funnyordie\.com/videos/.+~i',
			'Gist' => '~gist\.github\.com/.+/[0-9]+~i',
			'Gmep' => '~gmep\.org/media/.+~i',
			'HowCast' => '~howcast\.com/.+/.+~i',
			'Huffduffer' => '~huffduffer\.com/[-.\w@]+/\d+~i',
			'Hulu' => '~hulu\.com/watch/.+~i',
			'Ifixit' => '~ifixit\.com/.+~i',
			'Ifttt' => '~ifttt\.com/recipes/.+~i',
			'Imgur' => '~(imgur\.com/(gallery|a)/.+|imgur\.com/.+)~i',
			'InstagramOEmbed' => '~instagr(\.am|am\.com)/p/.+~i',
			'InstagramOpenGraph' => '~instagr(\.am|am\.com)/p/.+~i',
			'Jest' => '~jest\.com/(video|embed)/.+~i',
			'Justin' => '~justin\.tv/.+~i',
			'Kickstarter' => '~kickstarter\.com/projects/.+~i',
			'Meetup' => '~meetup\.(com|ps)/.+~i',
			'Mixcloud' => '~mixcloud\.com/.+/.+~i',
			'Mobypicture' => '~(moby.to|mobypicture\.com/user/.+/view)/.+~i',
			'Nfb' => '~nfb\.ca/films?/.+~i',
			'Official.fm' => '~official\.fm/.+~i',
			'Polldaddy' => '~polldaddy\.com/.+~i',
			'PollEverywhere' => '~polleverywhere\.com/(polls|multiple_choice_polls|free_text_polls)/.+~i',
			'Prezi' => '~prezi\.com/.+/.+~i',
			'Qik' => '~qik\.com/\w+~i',
			'Rdio' => '~rdio\.com/(artist|people)/.+~i',
			'Revision3' => '~revision3\.com/[a-z0-9]+/.+~i',
			'Roomshare' => '~roomshare\.jp(/en)?/post/.+~i',
			'Sapo' => '~videos\.sapo\.pt/.+~i',
			'Screenr' => '~screenr\.com/.+~i',
			'Scribd' => '~scribd\.com/doc/[0-9]+/.+~i',
			'Shoudio' => '~(shoudio\.com|shoud\.io)/.+~i',
			'Sketchfab' => '~sketchfab\.com/models/.+~i',
			'SlideShare' => '~slideshare\.net/.+/.+~i',
			'SoundCloud' => '~soundcloud\.com/[a-zA-Z0-9-_]+/[a-zA-Z0-9-]+~i',
			'SpeakerDeck' => '~speakerdeck\.com/.+/.+~i',
			'Spotify' => '~(open|play)\.spotify\.com/.+~i',
			'TedOEmbed' => '~ted\.com/talks/.+~i',
			'TedOpenGraph' => '~ted\.com/talks/.+~i',
			'Twitter' => '~twitter\.com/[a-zA-Z0-9_]+/status(es)?/.+~i',
			'Ustream' => '~ustream\.(tv|com)/.+~i',
			'Vhx' => '~vhx\.tv/.+~i',
			'Viddler' => '~viddler\.com/.+~i',
			'Videojug' => '~videojug\.com/(film|interview)/.+~i',
			'Vimeo' => '~vimeo\.com~i',
			'Vine' => '~^https?://vine.co/v/[a-zA-Z0-9]+~i',
			'Wistia' => '~https?://(.+)?(wistia.com|wi.st)/.*~i',
			'WordPress' => '~wordpress\.com/.+~i',
			'Yfrog' => '~yfrog\.(com|ru|com\.tr|it|fr|co\.il|co\.uk|com\.pl|pl|eu|us)/.+~i',
			'Youtube' => '~youtube\.com|youtu\.be~i'
		]);
	}
}
