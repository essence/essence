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
<?php

require_once 'path/to/essence/bootstrap.php';

$Essence = new fg\Essence\Essence( );

$Media = $Essence->embed( 'http://www.youtube.com/watch?v=39e3KYAmXK4' );

if ( $Media ) {
	// That's all, you're good to go !
}

?>
```

Then, just do anything you want with the data:

```php
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

```php
<?php

if ( !$Media->has( 'foo' )) {
	$Media->set( 'foo', 'bar' );
}

$value = $Media->get( 'foo' );

// Or through the $properties array

$Media->properties['foo'] = 'bar';

// Or directly like a class attribute

$Media->customValue = 12;

?>
```

While some open graph properties do not exactly match the Oembed specification, Essence ensures that the html variable is always available. Please note where a page contains multiple media types, Essence creates the html variable based on the assumption that videos are preferred over images which are preferred over links.

Configuration
-------------

Essence currently supports 36 specialized providers:

```
23hq, Bandcamp, Blip.tv, Cacoo, CanalPlus, Chirb.it, Clikthrough, CollegeHumour, Dailymotion, Deviantart, Dipity, Flickr, Funnyordie, Howcast, Huffduffer, Hulu, Ifixit, Imgur, Instagram, Mobypicture, Official.fm, Polldaddy, Qik, Revision3, Scribd, Shoudio, Sketchfab, Slideshare, SoundCloud, Ted, Twitter, Vhx, Viddler, Vimeo, Yfrog and Youtube.
```

And two generic ones which will try to get informations about any page.

If you know which providers you will have to query, or simply want to exclude some of them, you can tell Essence which ones you want to use:

```php
<?php

$Essence = new fg\Essence\Essence(
	array(
		'OEmbed/Youtube',
		'OEmbed/Dailymotion',
		'OpenGraph/Ted',
		'\YourCustomProvider'
	)
);

?>
```

You can add custom providers by adding a FQCN (Fully-Qualified Class Name) to the list of providers.

When given an array of providers, the constructor might throw an exception if a provider couldn't be found or loaded.
If you want to make your code rock solid, you should better wrap that up in a try/catch statement:

```php
<?php

try {
	$Essence = new fg\Essence\Essence( array( ... ));
} catch ( fg\Essence\Exception $Exception ) {
	...
}

?>
```

Advanced usage
--------------

### Extracting URLs

The Essence class provides some useful utility function to ensure you will get some informations.

First, the extract( ) method lets you extract embeddable URLs from a web page.
For example, say you want to get the URL of all videos in a blog post:

```php
<?php

$urls = $Essence->extract( 'http://www.blog.com/article' );

/**
 *	$urls now contains all URLs that can be extracted by Essence:
 *
 *	array(
 *		'http://www.youtube.com/watch?v=123456'
 *		'http://www.dailymotion.com/video/a1b2c_lolcat-fun'
 *	)
 */

?>
```

Now that you've got those URLs, there is a good chance you want to embed them:

```php
<?php

$medias = $Essence->embedAll( $urls );

/**
 *	$medias contains an array of Media objects indexed by URL:
 *
 *	array(
 *		'http://www.youtube.com/watch?v=123456' => Media( ... )
 *		'http://www.dailymotion.com/video/a1b2c_lolcat-fun' => Media( ... )
 *	)
 */

?>
```

### Replacing URLs in text

Essence can replace any embeddable URL in a text by informations about it.
Consider this piece of content:

```html
Check out this video: http://www.youtube.com/watch?v=123456
```

If you just pass this text to the replace( ) method, the URL will be replaced by the HTML code for the video player.
But you can do more by defining a template to control which informations will replace the URL.

```php
<?php

echo $Essence->replace( $text, '<p class="title">%title%</p><div class="player">%html%</div>' );

?>
```

This call should print something like that:

```html
Check out this video:
<p class="title">Video title</p>
<div class="player">
	<iframe src="http://www.youtube.com/embed/123456"></iframe>
<div>
```

Note that you can use any property of the Media class in the template (See the [What you get](https://github.com/felixgirault/essence#what-you-get "Available properties") section).

### Configuring providers

It is possible to pass some options to the providers.

For example, OEmbed providers accepts the `maxwidth` and `maxheight` parameters, as specified in the OEmbed spec.
Other providers will just ignore the options they don't handle.

```php
<?php

$Media = $Essence->embed(
	'http://www.youtube.com/watch?v=abcdef',
	array(
		'maxwidth' => 800,
		'maxheight' => 600
	)
);

$medias = $Essence->embedAll(
	array(
		'http://www.youtube.com/watch?v=abcdef',
		'http://www.youtube.com/watch?v=123456'
	),
	array(
		'maxwidth' => 800,
		'maxheight' => 600
	)
);

?>
```

### Error handling

By default, Essence does all the dirty stuff for you by catching all internal exceptions, so you just have to test if an Media object is valid.
But, in case you want more informations about an error, Essence keeps exceptions warm, and lets you access all of them:

```php
<?php

$Media = $Essence->embed( 'http://www.youtube.com/watch?v=oHg5SJYRHA0' );

if ( !$Media ) {
	$Exception = $Essence->lastError( );

	echo 'That\'s why you should never trust a camel: ', $Exception->getMessage( );
}

?>
```

Third-party libraries
---------------------

* CakePHP plugin: https://github.com/felixgirault/cakephp-essence
* Demo framework by Sean Steindl: https://github.com/laughingwithu/Essence_demo
* Symfony bundle by Ka Yue Yeung: https://github.com/kayue/KayueEssenceBundle
