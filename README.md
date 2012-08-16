Essence
=======

Essence is a simple PHP library to extract embed informations from websites.

Example
-------

Essence is designed to be really easy to use.
Using the main class of the library, you can retrieve embed informations in just those few lines:

```php
<?php

// First, tell Essence which providers you want to use

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
