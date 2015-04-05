Essence
=======

[![Build status](http://img.shields.io/travis/felixgirault/essence.svg?style=flat-square)](http://travis-ci.org/felixgirault/essence)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/felixgirault/essence.svg?style=flat-square)](https://scrutinizer-ci.com/g/felixgirault/essence)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/felixgirault/essence.svg?style=flat-square)](https://scrutinizer-ci.com/g/felixgirault/essence)
[![Total downloads](http://img.shields.io/packagist/dt/fg/essence.svg?style=flat-square)](https://packagist.org/packages/fg/essence)

Essence is a simple PHP library to extract media information from websites, like youtube videos, twitter statuses or blog articles.

If you were already using Essence 2.x.x, you should take a look at [the migration guide](https://github.com/felixgirault/essence/wiki/Migrating-from-2.x.x-to-3.x.x).

Installation
------------

```
composer require fg/essence
```

Example
-------

Essence is designed to be really easy to use.
Using the main class of the library, you can retrieve information in just those few lines:

```php
$Essence = new Essence\Essence();
$Media = $Essence->extract('http://www.youtube.com/watch?v=39e3KYAmXK4');

if ($Media) {
	// That's all, you're good to go !
}
```

Then, just do anything you want with the data:

```html+php
<article>
	<header>
		<h1><?php echo $Media->title; ?></h1>
		<p>By <?php echo $Media->authorName; ?></p>
	</header>

	<div class="player">
		<?php echo $Media->html; ?>
	</div>
</article>
```

What you get
------------

Using Essence, you will mainly interact with Media objects.
Media is a simple container for all the information that are fetched from an URL.

Here are the default properties it provides:

* type
* version
* url
* title
* description
* authorName
* authorUrl
* providerName
* providerUrl
* cacheAge
* thumbnailUrl
* thumbnailWidth
* thumbnailHeight
* html
* width
* height

These properties were gathered from the OEmbed and OpenGraph specifications, and merged together in a united interface.
Based on such standards, these properties should be a solid starting point.

However, "non-standard" properties can and will also be setted.

Here is how you can manipulate the Media properties:

```php
// through dedicated methods
if (!$Media->has('foo')) {
	$Media->set('foo', 'bar');
}

$value = $Media->get('foo');

// or directly like a class attribute
$Media->customValue = 12;
```

Note that Essence will always try to fill the `html` property when it is not available.

Advanced usage
--------------

The Essence class provides some useful utility functions to ensure you will get some information.

### Extracting URLs

The `crawl()` method lets you extract embeddable URLs from a web page.

For example, here is how you could get the URL of all videos in a blog post:

```php
$urls = $Essence->crawl('http://www.blog.com/article');
```
```
array(2) {
	[0] => 'http://www.youtube.com/watch?v=123456',
	[1] => 'http://www.dailymotion.com/video/a1b2c_lolcat-fun'
}
```

You can then get information from all the extracted URLs:

```php
$medias = $Essence->extractAll($urls);
```
```
array(2) {
	['http://www.youtube.com/watch?v=123456'] => object(Media) {}
	['http://www.dailymotion.com/video/a1b2c_lolcat-fun'] => object(Media) {}
}
```

### Replacing URLs in text

Essence can replace any embeddable URL in a text by information about it.
By default, any URL will be replaced by the `html` property of the found Media.

```php
echo $Essence->replace('Look at this: http://www.youtube.com/watch?v=123456');
```
```html
Look at this: <iframe src="http://www.youtube.com/embed/123456"></iframe>
```

But you can do more by passing a callback to control which information will replace the URL:

```php
echo $Essence->replace($text, function($Media) {
	return <<<HTML
		<p class="title">$Media->title</p>
		<div class="player">$Media->html</div>
HTML;
});
```
```html
Look at this:
<p class="title">Video title</p>
<div class="player">
	<iframe src="http://www.youtube.com/embed/123456"></iframe>
<div>
```

This makes it easy to build rich templates or even to integrate a templating engine:

```php
echo $Essence->replace($text, function($Media) use ($TwigTemplate) {
	return $TwigTemplate->render($Media->properties());
});
```

### Configuring providers

It is possible to pass some options to the providers.

For example, OEmbed providers accepts the `maxwidth` and `maxheight` parameters, as specified in the OEmbed spec.

```php
$options = [
	'maxwidth' => 800,
	'maxheight' => 600
];

$Media = $Essence->extract($url, $options);
$medias = $Essence->extractAll($urls, $options);
$text = $Essence->replace($text, null, $options);
```

Other providers will just ignore the options they don't handle.

Configuration
-------------

Essence currently supports 68 specialized providers:

```html
23hq                Deviantart          Kickstarter         Sketchfab
Animoto             Dipity              Meetup              SlideShare
Aol                 Dotsub              Mixcloud            SoundCloud
App.net             Edocr               Mobypicture         SpeakerDeck
Bambuser            Flickr              Nfb                 Spotify
Bandcamp            FunnyOrDie          Official.fm         Ted
Blip.tv             Gist                Polldaddy           Twitter
Cacoo               Gmep                PollEverywhere      Ustream
CanalPlus           HowCast             Prezi               Vhx
Chirb.it            Huffduffer          Qik                 Viddler
CircuitLab          Hulu                Rdio                Videojug
Clikthrough         Ifixit              Revision3           Vimeo
CollegeHumor        Ifttt               Roomshare           Vine
Coub                Imgur               Sapo                Wistia
CrowdRanking        Instagram           Screenr             WordPress
DailyMile           Jest                Scribd              Yfrog
Dailymotion         Justin.tv           Shoudio             Youtube
```

Plus the `OEmbed` and `OpenGraph` providers, which can be used to extract any URL.

You can configure these providers by passing a configuration array:

```php
$Essence = new Essence\Essence([
	'providers' => [
		// the OpenGraph provider will try to extract any URL that matches
		// the filter
		'Ted' => [
			'class' => 'OpenGraph',
			'filter' => '#ted\.com/talks/.*#i'
		],

		// the OEmbed provider will query the endpoint, :url beeing replaced
		// by the requested URL.
		'Youtube' => [
			'class' => 'OEmbed',
			'filter' => '#youtube\.com/.*#',
			'endpoint' => 'http://www.youtube.com/oembed?format=json&url=:url'
		]
	]
]);
```

You can also load a configuration array from a file:

```php
$Essence = new Essence\Essence([
	'providers' => 'path/to/config/file.php'
]);
```

You can use custom providers by specifying a fully-qualified class name in the 'class' option.

If no configuration is provided, the default configuration will be loaded from the `config/providers.json` file.

Customization
-------------

Almost everything in Essence can be configured through dependency injection.
Under the hoods, the constructor uses a dependency injection container to return a fully configured instance of Essence.

To customize the Essence behavior, the easiest way is to configure injection settings when building Essence:

```php
$Essence = new Essence\Essence([
	// the container will return a unique instance of CustomHttpClient
	// each time an HTTP client is needed
	'Http' => Essence\Di\Container::unique(function() {
		return new CustomHttpClient();
	})
]);
```

The default injection settings are defined in the [Standard](https://github.com/felixgirault/essence/blob/master/lib/Essence/Di/Container/Standard.php) container class.

Try it out
----------

Once you've installed essence, you should try to run `./cli/essence.php` in a terminal.
This script allows you to test Essence quickly:

```
# will fetch and print information about the video
./cli/essence.php extract http://www.youtube.com/watch?v=4S_NHY9c8uM

# will fetch and print all embeddable URLs found at the given HTML page
./cli/essence.php crawl http://www.youtube.com/watch?v=4S_NHY9c8uM
```

Third-party libraries
---------------------

If you're interested in embedding videos, you should take a look at the [Multiplayer](https://github.com/felixgirault/multiplayer) lib.
It allows you to build customizable embed codes painlessly:

```php
$Multiplayer = new Multiplayer\Multiplayer();

if ($Media->type === 'video') {
	echo $Multiplayer->html($Media->url, [
		'autoPlay' => true,
		'highlightColor' => 'BADA55'
	]);
}
```
