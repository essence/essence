Essence
=======

Essence is a simple PHP library to extract embed informations from websites.

Example
-------

Essence is designed to be really easy to use.
Using the main class of the library, you can retrieve embed informations in just those few lines:

```php
<?php

// First, configure Essence with the providers you want to use

require_once 'path/to/essence/bootstrap.php';

Essence\Essence::configure(
	'OEmbed/Youtube',
	'OEmbed/Dailymotion',
	'OpenGraph/Ted'
);

// Then, ask for informations about an URL

$Embed = Essence\Essence::fetch( 'http://www.youtube.com/watch?v=oHg5SJYRHA0' );

if ( $Embed ) {
	// That's all, you're good to go !
}

?>
```

Then, just do anything you want with the data:

```php
<article>
	<header>
		<h1><?php echo $Embed->title; ?></h1>
		<p>By <?php echo $Embed->authorName; ?></p>
	</header>

	<div class="player">
		<?php echo $Embed->html; ?>
	</div>
</article>
```

What you get
------------

With Essence, you will mainly interact with Embed objects.
Embed is a simple container for all the informations that are fetched from an URL.

Here is the default properties it provides:

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
* thumbnailWith
* thumbnailHeight
* html
* width
* height
 
These properties were gathered from the OEmbed and OpenGraph specifications, and merged together in a united interface.
Therefore, based on such standards, these properties are a solid starting point.

However, some providers could also provide some other properties that you want to get.
Don't worry, all these "non-standard" properties can also be stored in a Embed object.

```php
<?php

if ( $Embed->hasCustomProperty( 'a_custom_property' )) {
	$Embed->setCustomProperty( 'a_custom_property', 'value' );
}

$value = $Embed->getCustomProperty( 'a_custom_property' );

?>
```

Advanced usage
--------------

The Essence class provides some useful utility function to ensure you will get some informations.

First, the extract( ) method lets you extract embeddable URLs from a web page.
For example, say you want to get the URL of all videos in a blog post:

```php
<?php

// Don't forget to configure Essence first !

$urls = Essence\Essence::extract( 'http://www.blog.com/article' );

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

$embeds = Essence\Essence::fetchAll( $urls );

/**
 *	$embeds contains an array of Embed objects indexed by URL:
 *	
 *	array(
 *		'http://www.youtube.com/watch?v=123456' => Embed( )
 *		'http://www.dailymotion.com/video/a1b2c_lolcat-fun' => Embed( )
 *	)
 */
 
?>
```

Error handling
--------------

By default, Essence does all the dirty stuff for you by catching all internal exceptions, so you just have to test if an Embed object is valid.
But, in case you want more informations about an error, Essence keeps exceptions warm, and lets you access all of them:
```php
<?php

// Essence configuration

$Embed = Essence\Essence::fetch( 'http://www.youtube.com/watch?v=oHg5SJYRHA0' );

if ( !$Embed ) {
	$Exception = Essence\Essence::lastError( );
	echo 'That\'s why you should never trust a camel: ', $Exception->getMessage( );
}

?>
```