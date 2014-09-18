<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Extractor;



/**
 *	Replaces URLs in text by related informations.
 */
class Replacer {

	/**
	 *	Extractor.
	 *
	 *	@var Extractor
	 */
	protected $_Extractor = null;



	/**
	 *	A pattern to match URLs.
	 *
	 *	@see http://daringfireball.net/2010/07/improved_regex_for_matching_urls
	 *	@var string
	 */
	protected $_urlPattern =
		'~
			(?<!=["\']) # avoids matching URLs in HTML attributes
			(?:https?:)//
			(?:www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)?
			(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+
			(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'"\.,<>?«»“”‘’])
		~ix';



	/**
	 *	Constructor.
	 *
	 *	@param Extractor $Extractor Extractor.
	 */
	public function __construct(Extractor $Extractor) {
		$this->_Extractor = $Extractor;
	}



	/**
	 *	Sets the URL pattern for replacements.
	 *
	 *	@see replace()
	 *	@param string $pattern URL pattern.
	 */
	public function setUrlPattern($pattern) {
		$this->_urlPattern = $pattern;
	}



	/**
	 *	Replaces URLs in the given text by media informations if they point on
	 *	an embeddable resource.
	 *	By default, links will be replaced by the html property of Media.
	 *	If $template is a callable function, it will be used to generate
	 *	replacement strings, given a Media object.
	 *
	 *	@code
	 *	$text = $Essence->replace($text, function($Media) {
	 *		return '<div class="title">' . $Media->title . '</div>';
	 *	});
	 *	@endcode
	 *
	 *	This behavior should make it easy to integrate third party templating
	 *	engines.
	 *	The pattern to match urls can be configured throught setUrlPattern().
	 *
	 *	@param string $text Text in which to replace URLs.
	 *	@param callable $template Templating callback.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return string Text with replaced URLs.
	 */
	public function replace($text, $template = null, array $options = []) {
		if (!is_callable($template)) {
			$template = $this->_defaultTemplate();
		}

		return preg_replace_callback(
			$this->_urlPattern,
			$this->_replaceFunction($template, $options),
			$text
		);
	}



	/**
	 *	Returns a default templating callback for replace().
	 *
	 *	@see replace()
	 *	@return \Closure Templating function.
	 */
	protected function _defaultTemplate() {
		return function($Media) {
			return $Media->get('html');
		};
	}



	/**
	 *	Returns a function used to replace URLs by informations.
	 *
	 *	@see replace()
	 *	@param callable $template Templating callback.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return \Closure Function.
	 */
	protected function _replaceFunction($template, array $options) {
		return function($matches) use ($template, $options) {
			$url = $matches[0];
			$Media = $this->_Extractor->extract($url, $options);

			return $Media
				? $template($Media)
				: $url;
		};
	}
}
