<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

use Essence\Provider\OEmbed;



/**
 *	Default providers configuration.
 *
 *	@see Essence\ProviderCollection::$_config
 *	@var array
 */

return array(
	'23hq' => array(
		'class' => 'OEmbed',
		'filter' => '#23hq.com/.+/photo/.+#i',
		'endpoint' => 'http://www.23hq.com/23/oembed?format=json&url=%s',
	),
	'Bandcamp' => array(
		'class' => 'OpenGraph',
		'filter' => '#bandcamp\.com/(album|track)/#i'
	),
	'Blip.tv' => array(
		'class' => 'OEmbed',
		'filter' => '#blip.tv/.+#i',
		'endpoint' => 'http://blip.tv/oembed?format=json&url=%s',
	),
	'Cacoo' => array(
		'class' => 'OEmbed',
		'filter' => '#cacoo.com/.+#i',
		'endpoint' => 'http://cacoo.com/oembed.json?url=%s',
	),
	'CanalPlus' => array(
		'class' => 'OpenGraph',
		'filter' => '#canalplus\.fr#i'
	),
	'Chirb.it' => array(
		'class' => 'OEmbed',
		'filter' => '#chirb.it/.+#i',
		'endpoint' => 'http://chirb.it/oembed.json?url=%s',
	),
	'Clikthrough' => array(
		'class' => 'OEmbed',
		'filter' => '#clikthrough\.com/theater/video/\d+#i',
		'endpoint' => 'http://clikthrough.com/services/oembed?format=json&url=%s',
	),
	'CollegeHumorOEmbed' => array(
		'class' => 'OEmbed',
		'filter' => '#collegehumor.com/(video|embed)/.*#i',
		'endpoint' => 'http://www.collegehumor.com/oembed.json?url=%s',
	),
	'CollegeHumorOpenGraph' => array(
		'class' => 'OpenGraph',
		'filter' => '#collegehumor.com/(picture|article)/.+#i'
	),
	'Dailymotion' => array(
		'class' => 'OEmbed',
		'filter' => '#dailymotion\.com#i',
		'endpoint' => 'http://www.dailymotion.com/services/oembed?format=json&url=%s',
	),
	'Deviantart' => array(
		'class' => 'OEmbed',
		'filter' => '#deviantart.com/.+#i',
		'endpoint' => 'http://backend.deviantart.com/oembed?format=json&url=%s',
	),
	'Dipity' => array(
		'class' => 'OEmbed',
		'filter' => '#dipity.com/.+#i',
		'endpoint' => 'http://www.dipity.com/oembed/timeline?format=json&url=%s',
	),
	'Flickr' => array(
		'class' => 'OEmbed',
		'filter' => '#flickr\.com/photos/[a-zA-Z0-9@\\._]+/[0-9]+#i',
		'endpoint' => 'http://flickr.com/services/oembed?format=json&url=%s',
	),
	'FunnyOrDie' => array(
		'class' => 'OEmbed',
		'filter' => '#funnyordie\.com/videos/.*#i',
		'endpoint' => 'http://www.funnyordie.com/oembed?format=json&url=%s',
	),
	'HowCast' => array(
		'class' => 'OpenGraph',
		'filter' => '#howcast\.com/.+/.+#i'
	),
	'Huffduffer' => array(
		'class' => 'OEmbed',
		'filter' => '#huffduffer.com/[-.\w@]+/\d+#i',
		'endpoint' => 'http://huffduffer.com/oembed?format=json&url=%s',
	),
	'Hulu' => array(
		'class' => 'OEmbed',
		'filter' => '#hulu\.com/watch/.+#i',
		'endpoint' => 'http://www.hulu.com/api/oembed.json?url=%s',
	),
	'Ifixit' => array(
		'class' => 'OEmbed',
		'filter' => '#ifixit.com/.*#i',
		'endpoint' => 'http://www.ifixit.com/Embed?format=json&url=%s',
	),
	'Imgur' => array(
		'class' => 'OEmbed',
		'filter' => '#(imgur\.com/(gallery|a)/.+|imgur\.com/.+)#i',
		'endpoint' => 'http://api.imgur.com/oembed?format=json&url=%s',
	),
	'Instagram' => array(
		'class' => 'OEmbed',
		'filter' => '#instagr(\.am|am\.com)/p/.*#i',
		'endpoint' => 'http://api.instagram.com/oembed?format=json&url=%s',
	),
	'Mobypicture' => array(
		'class' => 'OEmbed',
		'filter' => '#mobypicture.com/user/.+/view/.+#','moby.to/.+#i',
		'endpoint' => 'http://api.mobypicture.com/oEmbed?format=json&url=%s',
	),
	'Official.fm' => array(
		'class' => 'OEmbed',
		'filter' => '#official.fm/.+#i',
		'endpoint' => 'http://official.fm/services/oembed?format=json&url=%s',
	),
	'Polldaddy' => array(
		'class' => 'OEmbed',
		'filter' => '#polldaddy\.com/.*#i',
		'endpoint' => 'http://polldaddy.com/oembed?format=json&url=%s',
	),
	'Prezi' => array(
		'class' => 'OpenGraph',
		'filter' => '#prezi\.com/.+/.+#i'
	),
	'Qik' => array(
		'class' => 'OEmbed',
		'filter' => '#qik\.com/\w+#i',
		'endpoint' => 'http://qik.com/api/oembed.json?url=%s',
	),
	'Revision3' => array(
		'class' => 'OEmbed',
		'filter' => '#revision3\.com/[a-z0-9]+/.+#i',
		'endpoint' => 'http://revision3.com/api/oembed?format=json&url=%s',
	),
	'Scribd' => array(
		'class' => 'OEmbed',
		'filter' => '#scribd\.com/doc/[0-9]+/.+#i',
		'endpoint' => 'http://www.scribd.com/services/oembed?format=json&url=%s',
	),
	'Shoudio' => array(
		'class' => 'OEmbed',
		'filter' => '#(shoudio.com/.+|shoud.io/.+)#i',
		'endpoint' => 'http://shoudio.com/api/oembed?format=json&url=%s',
	),
	'Sketchfab' => array(
		'class' => 'OEmbed',
		'filter' => '#sketchfab.com/show/.+#i',
		'endpoint' => 'http://sketchfab.com/oembed?format=json&url=%s',
	),
	'SlideShare' => array(
		'class' => 'OEmbed',
		'filter' => '#slideshare\.net/.+/.+#i',
		'endpoint' => 'http://www.slideshare.net/api/oembed/2?format=json&url=%s',
	),
	'SoundCloud' => array(
		'class' => 'OEmbed',
		'filter' => '#soundcloud\.com/[a-zA-Z0-9-]+/[a-zA-Z0-9-]+#i',
		'endpoint' => 'http://soundcloud.com/oembed?format=json&url=%s',
	),
	'TedOEmbed' => array(
		'class' => 'OEmbed',
		'filter' => '#ted.com/talks/*+#i',
		'endpoint' => 'http://www.ted.com/talks/oembed.json?url=%s',
	),
	'TedOpenGraph' => array(
		'class' => 'OpenGraph',
		'filter' => '#ted\.com/talks#i'
	),
	'Twitter' => array(
		'class' => 'OEmbed',
		'filter' => '#twitter\.com/[a-zA-Z0-9_]+/status/.+#i',
		'endpoint' => 'https://api.twitter.com/1/statuses/oembed.json?url=%s',
	),
	'Vhx' => array(
		'class' => 'OEmbed',
		'filter' => '#vhx.tv/.+#i',
		'endpoint' => 'http://vhx.tv/services/oembed.json?url=%s',
	),
	'Viddler' => array(
		'class' => 'OEmbed',
		'filter' => '#viddler.com/.+#i',
		'endpoint' => 'http://www.viddler.com/oembed/?url=%s',
	),
	'Vimeo' => array(
		'class' => 'OEmbed',
		'filter' => '#vimeo\.com#i',
		'endpoint' => 'http://vimeo.com/api/oembed.json?url=%s',
		'prepare' => function( $url ) {

			/**
			 *	Refactors URLs like these:
			 *	- http://player.vimeo.com/video/20830433
			 *
			 *	in such form:
			 *	- http://www.vimeo.com/20830433
			 */

			$url = OEmbed::prepareUrl( $url );

			if ( preg_match( '#player\.vimeo\.com/video/(?<id>[0-9]+)#i', $url, $matches )) {
				$url = 'http://www.vimeo.com/' . $matches['id'];
			}

			return $url;
		}
	),
	'Yfrog' => array(
		'class' => 'OEmbed',
		'filter' => '#yfrog\.(com|ru|com\.tr|it|fr|co\.il|co\.uk|com\.pl|pl|eu|us)/.+#i',
		'endpoint' => 'http://www.yfrog.com/api/oembed?format=json&url=%s',
	),
	'Youtube' => array(
		'class' => 'OEmbed',
		'filter' => '#youtube\.com|youtu\.be#i',
		'endpoint' => 'http://www.youtube.com/oembed?format=json&url=%s',
		'prepare' => function( $url ) {

			/**
			 *	Refactors URLs like these:
			 *	- http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise
			 *	- http://www.youtube.com/v/oHg5SJYRHA0
			 *	- http://www.youtube.com/embed/oHg5SJYRHA0
			 *	- http://youtu.be/oHg5SJYRHA0
			 *
			 *	in such form:
			 *	- http://www.youtube.com/watch?v=oHg5SJYRHA0
			 */

			$url = trim( $url );

			if ( preg_match( '#(?:v=|v/|embed/|youtu\.be/)(?<id>[a-z0-9_-]+)#i', $url, $matches )) {
				$url = 'http://www.youtube.com/watch?v=' . $matches['id'];
			}

			return $url;
		}
	)

	/**
	 *	The following providers will try to embed any URL.
	 */

	/*
	'OEmbed' => array(
		'class' => 'OEmbed',
		'filter' => '#.*#'
	),
	'OpenGraph' => array(
		'class' => 'OpenGraph',
		'filter' => '#.*#'
	),
	*/
);
