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
	 *	A regex to identify variables in a template.
	 *
	 *	@var string
	 */
	const variablePattern = '~\{?:(?<variable>[a-z0-9_]+)\}?~i';



	/**
	 *	Compiles a template with variables.
	 *
	 *	@param string $template Template.
	 *	@param array $variables Variables.
	 *	@param callable $filter Filter function.
	 *	@return string Compiled template.
	 */
	public static function compile($template, array $variables, $filter = null) {
		return preg_replace_callback(
			self::variablePattern,
			self::_compileFunction($variables, $filter),
			$template
		);
	}



	/**
	 *	Returns a function used to replace variables by values.
	 *
	 *	@see compile()
	 *	@param array $variables Variables.
	 *	@param callable|null $filter Filter function.
	 *	@return \Closure Function.
	 */
	protected static function _compileFunction(array $variables, $filter) {
		return function ($matches) use ($variables, $filter) {
			$name = $matches['variable'];
			$value = isset($variables[$name])
				? $variables[$name]
				: '';

			return $filter
				? call_user_func($filter, $value)
				: $value;
		};
	}
}
