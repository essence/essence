Essence
=======

Essence is a simple PHP library to extract embed informations from websites.

Example
-------

Essence is designed to be really easy to use.
Using the main class of the library, you can retrieve embed informations in just those few lines:

```php
// First, let's tell Essence what providers you want to use

Essence\Essence::configure(
	'OEmbed/Youtube',
	'OEmbed/Dailymotion',
	'OpenGraph/Ted'
);

// Then, ask for informations about an URL

$Embed = Essence\Essence::fetch( 'http://www.youtube.com/watch?v=oHg5SJYRHA0' );

if ( $Embed !== null ) {
	// That's all, $Embed contains all the informations gathered from the URL !
}
```