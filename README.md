Essence
=======

[![Build status](https://secure.travis-ci.org/felixgirault/essence.png)](http://travis-ci.org/felixgirault/essence)
[![Total downloads](https://poser.pugx.org/fg/essence/d/total.png)](https://packagist.org/packages/fg/essence)

Essence is a simple PHP library to extract media informations from websites, like youtube videos, twitter statuses or blog articles.

* [Example](#example)
* [What you get](#what-you-get)
* [Configuration](#configuration)
* [Advanced usage](#advanced-usage)
* [Third-party libraries](#third-party-libraries)

Example
-------

Essence is designed to be really easy to use.
Using the main class of the library, you can retrieve informations in just those few lines:

```php
require_once 'path/to/essence/bootstrap.php';

$Essence = new Essence\Essence( );

$Media = $Essence->embed( 'http://www.youtube.com/watch?v=39e3KYAmXK4' );

if ( $Media ) {
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

With Essence, you will mainly interact with Media objects.
Media is a simple container for all the informations that are fetched from an URL.

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
Therefore, based on such standards, these properties are a solid starting point.

However, some providers could also provide some other properties that you want to get.
Don't worry, all these "non-standard" properties can also be stored in a Media object.

Here is how you can manipulate the Media properties:

```php
// through dedicated methods
if ( !$Media->has( 'foo' )) {
	$Media->set( 'foo', 'bar' );
}

$value = $Media->get( 'foo' );

// through the $properties attribute
$Media->properties['foo'] = 'bar';

// or directly like a class attribute
$Media->customValue = 12;
```

While some open graph properties do not exactly match the Oembed specification, Essence ensures that the html variable is always available. Please note where a page contains multiple media types, Essence creates the html variable based on the assumption that videos are preferred over images which are preferred over links.

Configuration
-------------

Essence currently supports 36 specialized providers:

```
23hq, Bandcamp, Blip.tv, Cacoo, CanalPlus, Chirb.it, Clikthrough, CollegeHumour, Dailymotion, Deviantart, Dipity, Flickr, Funnyordie, Howcast, Huffduffer, Hulu, Ifixit, Imgur, Instagram, Mobypicture, Official.fm, Polldaddy, Qik, Revision3, Scribd, Shoudio, Sketchfab, Slideshare, SoundCloud, Ted, Twitter, Vhx, Viddler, Vimeo, Yfrog and Youtube.
```

You can customize the Essence behavior by passing a configuration array:

```php
$Essence = new Essence\Essence(
	array(
		// the OpenGraph provider will try to embed any URL that matches the filter
		'Ted' => array(
			'class' => 'OpenGraph',
			'filter' => '#ted\.com/talks/.*#i'
		),

		// the OEmbed provider will query the endpoint, %s beeing replaced by
		// the requested URL.
		'Youtube' => array(
			'class' => 'OEmbed',
			'filter' => '#youtube\.com/.*#',
			'endpoint' => 'http://www.youtube.com/oembed?format=json&url=%s'
		)
	)
);
```

You can use custom providers by specifying a FQCN (Fully-Qualified Class Name) in the 'class' option.
The default configuration (loaded when Essence isn't configured) sits in lib/config.php.

Advanced usage
--------------

### Extracting URLs

The Essence class provides some useful utility function to ensure you will get some informations.

First, the extract( ) method lets you extract embeddable URLs from a web page.
For example, say you want to get the URL of all videos in a blog post:

```php
$urls = $Essence->extract( 'http://www.blog.com/article' );

//	array(
//		'http://www.youtube.com/watch?v=123456'
//		'http://www.dailymotion.com/video/a1b2c_lolcat-fun'
//	)
```

Now that you've got those URLs, there is a good chance you want to embed them:

```php
$medias = $Essence->embedAll( $urls );

//	array(
//		'http://www.youtube.com/watch?v=123456' => Media( ... )
//		'http://www.dailymotion.com/video/a1b2c_lolcat-fun' => Media( ... )
//	)
```

### Replacing URLs in text

Essence can replace any embeddable URL in a text by informations about it.
By default, any URL will be replaced by the html property of the found Media.

```php
$text = 'Check out this awesome video: http://www.youtube.com/watch?v=123456'

echo $Essence->replace( $text );

//	Check out this awesome video: <iframe src="http://www.youtube.com/embed/123456"></iframe>
```

But you can do more by passing a callback to control which informations will replace the URL:

```php
echo $Essence->replace( $text, function( $Media ) {
	return sprintf(
		'<p class="title">%s</p><div class="player">%s</div>',
		$Media->title,
		$Media->html
	);
});

//	Check out this awesome video:
//	<p class="title">Video title</p>
//	<div class="player">
//		<iframe src="http://www.youtube.com/embed/123456"></iframe>
//	<div>
```

This makes it easy to build rich templates or even to integrate a templating engine:

```php
echo $Essence->replace( $text, function( $Media ) use ( $TwigTemplate ) {
	return $TwigTemplate->render( $Media->properties );
});
```

### Configuring providers

It is possible to pass some options to the providers.

For example, OEmbed providers accepts the `maxwidth` and `maxheight` parameters, as specified in the OEmbed spec.

```php
$Media = $Essence->embed(
	$url,
	array(
		'maxwidth' => 800,
		'maxheight' => 600
	)
);

$medias = $Essence->embedAll(
	$urls,
	array(
		'maxwidth' => 800,
		'maxheight' => 600
	)
);

$Media = $Essence->extract(
	$text,
	null,
	array(
		'maxwidth' => 800,
		'maxheight' => 600
	)
);
```

Other providers will just ignore the options they don't handle.

Third-party libraries
---------------------

* CakePHP plugin: https://github.com/felixgirault/cakephp-essence
* Demo framework by Sean Steindl: https://github.com/laughingwithu/Essence_demo
* Symfony bundle by Ka Yue Yeung: https://github.com/kayue/KayueEssenceBundle

If you're interested in embedding videos, you should take a look at the [Multiplayer](https://github.com/felixgirault/multiplayer) lib.
It allows you to build customizable embed codes painlessly:

```php
$Multiplayer = new Multiplayer\Multiplayer( );

if ( $Media->type === 'video' ) {
	echo $Multiplayer->html(
		$Media->url,
		array(
			'autoPlay' => true,
			'highlightColor' => 'BADA55'
		)
	);
}
```
