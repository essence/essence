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
	 *	@param callable $filter Filter function.
	 *	@return string Compiled template.
	 */
	public static function compile($template, array $variables, $filter = false) {
		$replace = function ($matches) use ($variables, $filter) {
			$name = $matches['variable'];
			$value = '';

			if (isset($variables[$name])) {
				$value = $variables[$name];

				if ($filter) {
					$value = call_user_func($filter, $value);
				}
			}

			return $value;
		};

		return preg_replace_callback(self::variable, $replace, $template);
	}
}
