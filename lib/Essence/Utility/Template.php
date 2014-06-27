<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace Essence\Utility;



/**
 *	An utility class to compile templates.
 */

class Template {

	/**
	 *
	 */

	const variable = '#\{?:(?<variable>[a-z0-9_]+)\}?#i';



	/**
	 *	Compiles a template with variables.
	 *
	 *	@param string $template Template.
	 *	@param array $variables Variables.
	 *	@return string Compiled template.
	 */

	public static function compile( $template, array $variables ) {

		return preg_replace_callback(
			self::variable,
			function ( $matches ) use ( $variables ) {
				$name = $matches['variable'];

				return isset( $variables[ $name ])
					? htmlspecialchars( $variables[ $name ])
					: '';
			},
			$template
		);
	}
}
