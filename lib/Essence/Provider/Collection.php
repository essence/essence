<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use Essence\Di\Container;



/**
 *	A collection of providers which can find the provider of an url.
 */
class Collection {

	/**
	 *	Dependency injection container.
	 *
	 *	@var Container
	 */
	protected $_Container = null;



	/**
	 *	Constructor.
	 *
	 *	@param Container $Container Dependency injection container
	 *		used to build providers.
	 */
	public function __construct(Container $Container) {
		$this->_Container = $Container;
	}



	/**
	 *	Tells if a provider can handle the given url.
	 *
	 *	@param string $url An url which may be extracted.
	 *	@return boolean The url provider if any, otherwise null.
	 */
	public function hasProvider($url) {
		$filters = $this->_Container->get('filters');

		foreach ($filters as $filter) {
			if ($this->_matches($filter, $url)) {
				return true;
			}
		}

		return false;
	}



	/**
	 *	Finds providers of the given url.
	 *
	 *	@todo Use PHP generators to yield providers.
	 *	@param string $url An url which may be extracted.
	 *	@return array An array of Essence\Provider.
	 */
	public function providers($url) {
		$filters = $this->_Container->get('filters');

		foreach ($filters as $name => $filter) {
			if ($this->_matches($filter, $url)) {
				yield $this->_Container->get($name);
			}
		}
	}



	/**
	 *	Tells if an URL matches a filter.
	 *
	 *	@param string|callable $filter Regex or callback to filter URL.
	 *	@param string $url URL to filter.
	 *	@return boolean Whether the URL matches the filter or not.
	 */
	protected function _matches($filter, $url) {
		if (is_string($filter)) {
			return (bool)preg_match($filter, $url);
		}

		if (is_callable($filter)) {
			return call_user_func($filter, $url);
		}

		return false;
	}
}
