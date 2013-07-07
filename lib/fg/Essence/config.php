<?php

use fg\Essence\Provider\OEmbed;

return array(
	'23hq' => array(
		'class' => 'OEmbed',
		'pattern' => '#23hq.com/.+/photo/.+#i',
		'endpoint' => 'http://www.23hq.com/23/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Bandcamp' => array(
		'class' => 'OpenGraph',
		'pattern' => '#bandcamp\.com/(album|track)/#i'
	),
	'Blip.tv' => array(
		'class' => 'OEmbed',
		'pattern' => '#blip.tv/.+#i',
		'endpoint' => 'http://blip.tv/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Cacoo' => array(
		'class' => 'OEmbed',
		'pattern' => '#cacoo.com/.+#i',
		'endpoint' => 'http://cacoo.com/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'CanalPlus' => array(
		'class' => 'OpenGraph',
		'pattern' => '#canalplus\.fr#i'
	),
	'Chirb.it' => array(
		'class' => 'OEmbed',
		'pattern' => '#chirb.it/.+#i',
		'endpoint' => 'http://chirb.it/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'Clikthrough' => array(
		'class' => 'OEmbed',
		'pattern' => '#clikthrough\.com/theater/video/\d+#i',
		'endpoint' => 'http://clikthrough.com/services/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'CollegeHumorOEmbed' => array(
		'class' => 'OEmbed',
		'pattern' => '#collegehumor.com/(video|embed)/.*#i',
		'endpoint' => 'http://www.collegehumor.com/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'CollegeHumorOpenGraph' => array(
		'class' => 'OpenGraph',
		'pattern' => '#collegehumor.com/(picture|article)/.+#i'
	),
	'Dailymotion' => array(
		'class' => 'OEmbed',
		'pattern' => '#dailymotion\.com#i',
		'endpoint' => 'http://www.dailymotion.com/services/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Deviantart' => array(
		'class' => 'OEmbed',
		'pattern' => '#deviantart.com/.+#i',
		'endpoint' => 'http://backend.deviantart.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Dipity' => array(
		'class' => 'OEmbed',
		'pattern' => '#dipity.com/.+#i',
		'endpoint' => 'http://www.dipity.com/oembed/timeline?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Flickr' => array(
		'class' => 'OEmbed',
		'pattern' => '#flickr\.com/photos/[a-zA-Z0-9@\\._]+/[0-9]+#i',
		'endpoint' => 'http://flickr.com/services/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'FunnyOrDie' => array(
		'class' => 'OEmbed',
		'pattern' => '#funnyordie\.com/videos/.*#i',
		'endpoint' => 'http://www.funnyordie.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'HowCast' => array(
		'class' => 'OpenGraph',
		'pattern' => '#howcast\.com/.+/.+#i'
	),
	'Huffduffer' => array(
		'class' => 'OEmbed',
		'pattern' => '#huffduffer.com/[-.\w@]+/\d+#i',
		'endpoint' => 'http://huffduffer.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Hulu' => array(
		'class' => 'OEmbed',
		'pattern' => '#hulu\.com/watch/.+#i',
		'endpoint' => 'http://www.hulu.com/api/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'Ifixit' => array(
		'class' => 'OEmbed',
		'pattern' => '#ifixit.com/.*#i',
		'endpoint' => 'http://www.ifixit.com/Embed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Imgur' => array(
		'class' => 'OEmbed',
		'pattern' => '#(imgur\.com/(gallery|a)/.+|imgur\.com/.+)#i',
		'endpoint' => 'http://api.imgur.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Instagram' => array(
		'class' => 'OEmbed',
		'pattern' => '#instagr(\.am|am\.com)/p/.*#i',
		'endpoint' => 'http://api.instagram.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Mobypicture' => array(
		'class' => 'OEmbed',
		'pattern' => '#mobypicture.com/user/.+/view/.+#','moby.to/.+#i',
		'endpoint' => 'http://api.mobypicture.com/oEmbed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Official.fm' => array(
		'class' => 'OEmbed',
		'pattern' => '#official.fm/.+#i',
		'endpoint' => 'http://official.fm/services/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Polldaddy' => array(
		'class' => 'OEmbed',
		'pattern' => '#polldaddy\.com/.*#i',
		'endpoint' => 'http://polldaddy.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Prezi' => array(
		'class' => 'OpenGraph',
		'pattern' => '#prezi\.com/.+/.+#i'
	),
	'Qik' => array(
		'class' => 'OEmbed',
		'pattern' => '#qik\.com/\w+#i',
		'endpoint' => 'http://qik.com/api/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'Revision3' => array(
		'class' => 'OEmbed',
		'pattern' => '#revision3\.com/[a-z0-9]+/.+#i',
		'endpoint' => 'http://revision3.com/api/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Scribd' => array(
		'class' => 'OEmbed',
		'pattern' => '#scribd\.com/doc/[0-9]+/.+#i',
		'endpoint' => 'http://www.scribd.com/services/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Shoudio' => array(
		'class' => 'OEmbed',
		'pattern' => '#(shoudio.com/.+|shoud.io/.+)#i',
		'endpoint' => 'http://shoudio.com/api/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Sketchfab' => array(
		'class' => 'OEmbed',
		'pattern' => '#sketchfab.com/show/.+#i',
		'endpoint' => 'http://sketchfab.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'SlideShare' => array(
		'class' => 'OEmbed',
		'pattern' => '#slideshare\.net/.+/.+#i',
		'endpoint' => 'http://www.slideshare.net/api/oembed/2?format=json&url=%s',
		'format' => OEmbed::json
	),
	'SoundCloud' => array(
		'class' => 'OEmbed',
		'pattern' => '#soundcloud\.com/[a-zA-Z0-9-]+/[a-zA-Z0-9-]+#i',
		'endpoint' => 'http://soundcloud.com/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'TedOEmbed' => array(
		'class' => 'OEmbed',
		'pattern' => '#ted.com/talks/*+#i',
		'endpoint' => 'http://www.ted.com/talks/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'TedOpenGraph' => array(
		'class' => 'OpenGraph',
		'pattern' => '#ted\.com/talks#i'
	),
	'Twitter' => array(
		'class' => 'OEmbed',
		'pattern' => '#twitter\.com/[a-zA-Z0-9_]+/status/.+#i',
		'endpoint' => 'https://api.twitter.com/1/statuses/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'Vhx' => array(
		'class' => 'OEmbed',
		'pattern' => '#vhx.tv/.+#i',
		'endpoint' => 'http://vhx.tv/services/oembed.json?url=%s',
		'format' => OEmbed::json
	),
	'Viddler' => array(
		'class' => 'OEmbed',
		'pattern' => '#viddler.com/.+#i',
		'endpoint' => 'http://www.viddler.com/oembed/?url=%s',
		'format' => OEmbed::json
	),
	'Vimeo' => array(
		'class' => 'OEmbed',
		'pattern' => '#vimeo\.com#i',
		'endpoint' => 'http://vimeo.com/api/oembed.json?url=%s',
		'format' => OEmbed::json,
		'prepare' => function( $url ) {

			// Refactors URLs like these :
			// - http://player.vimeo.com/video/20830433
			// in such form :
			// - http://www.vimeo.com/20830433

			$url = OEmbed::prepare( $url );

			if ( preg_match( "#player\.vimeo\.com/video/([0-9]+)#i", $url, $matches )) {
				return 'http://www.vimeo.com/' . $matches[ 1 ];
			}

			return $url;
		}
	),
	'Yfrog' => array(
		'class' => 'OEmbed',
		'pattern' => '#yfrog\.(com|ru|com\.tr|it|fr|co\.il|co\.uk|com\.pl|pl|eu|us)/.+#i',
		'endpoint' => 'http://www.yfrog.com/api/oembed?format=json&url=%s',
		'format' => OEmbed::json
	),
	'Youtube' => array(
		'class' => 'OEmbed',
		'pattern' => '#youtube\.com|youtu\.be#i',
		'endpoint' => 'http://www.youtube.com/oembed?format=json&url=%s',
		'format' => OEmbed::json,
		'prepare' => function( $url ) {

			// Refactors URLs like these :
			// - http://www.youtube.com/watch?v=oHg5SJYRHA0&noise=noise
			// - http://www.youtube.com/v/oHg5SJYRHA0
			// - http://www.youtube.com/embed/oHg5SJYRHA0
			// - http://youtu.be/oHg5SJYRHA0
			// in such form :
			// - http://www.youtube.com/watch?v=oHg5SJYRHA0

			$url = OEmbed::prepare( $url );

			if ( preg_match( "#(v=|v/|embed/|youtu\.be/)([a-z0-9_-]+)#i", $url, $matches )) {
				return 'http://www.youtube.com/watch?v=' . $matches[ 2 ];
			}

			return $url;
		}
	)

	// generic OEmbed

	/*
	'OEmbed' => array(
		'class' => 'OEmbed',
		'pattern' => '#.*#'
	),
	*/

	// generic OpenGraph

	/*
	'OpenGraph' => array(
		'class' => 'OpenGraph',
		'pattern' => '#.*#'
	),
	*/
);
