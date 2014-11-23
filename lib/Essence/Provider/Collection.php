<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Provider;

use Essence\Di\Container;
use Essence\Mixin\Configurable;
use Essence\Utility\Json;
use Exception;



/**
 *	A collection of providers which can find the provider of an url.
 */
class Collection {

	use Configurable;



	/**
	 *	Dependency injection container.
	 *
	 *	@var Container
	 */
	protected $_Container = null;



	/**
	 *	A list of provider configurations.
	 *
	 *	### Options
	 *
	 *	- 'name' string Name of the provider.
	 *		- 'class' string The provider class.
	 *		- 'filter' string|callable A regex or callback to filter URLs
	 *			that will be processed by the provider.
	 *		- ... mixed Provider specific options.
	 *
	 *	@var array
	 */
	protected $_properties = [];



	/**
	 *	A list of providers.
	 *
	 *	@var array
	 */
	protected $_providers = [];



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
	 *	Loads configuration from an array or a file.
	 *
	 *	@param array|string $config A configuration array, or a JSON
	 *		configuration file.
	 */
	public function load($config) {
		if (is_string($config) && file_exists($config)) {
			$json = file_get_contents($config);
			$config = Json::parse($json);
		}

		$this->configure($config);
	}



	/**
	 *	Tells if a provider can handle the given url.
	 *
	 *	@param string $url An url which may be embedded.
	 *	@return boolean The url provider if any, otherwise null.
	 */
	public function hasProvider($url) {
		foreach ($this->_properties as $config) {
			if ($this->_matches($config['filter'], $url)) {
				return true;
			}
		}

		return false;
	}



	/**
	 *	Finds providers of the given url.
	 *
	 *	@todo Use PHP generators to yield providers.
	 *	@param string $url An url which may be embedded.
	 *	@return array An array of Essence\Provider.
	 */
	public function providers($url) {
		foreach ($this->_properties as $name => $config) {
			if ($this->_matches($config['filter'], $url)) {
				yield $this->_provider($name, $config);
			}
		}
	}



	/**
	 *	Tells if an URL matches a filter.
	 *
	 *	@param string|callable $filter Regex or callback to filter URL.
	 *	@param string $url URL to filter.
	 *	@return Whether the URL matches the filter or not.
	 */
	protected function _matches($filter, $url) {
		return is_callable($filter)
			? call_user_func($filter, $url)
			: preg_match($filter, $url);
	}



	/**
	 *	Lazy loads a provider given its name and configuration.
	 *
	 *	@param string $name Name.
	 *	@param string $config Configuration.
	 *	@return Provider Instance.
	 */
	protected function _provider($name, $config) {
		if (!isset($this->_providers[$name])) {
			$this->_providers[$name] = $this->_buildProvider($config);
		}

		return $this->_providers[$name];
	}



	/**
	 *	Constructs a provider given its configuration.
	 *
	 *	@param string $config Configuration.
	 *	@return Provider Instance.
	 */
	protected function _buildProvider($config) {
		$name = $config['class'];

		if (!$this->_Container->has($name)) {
			throw new Exception("The '$name' provider is not configured.");
		}

		$Provider = $this->_Container->get($name);
		$Provider->configure($config);

		return $Provider;
	}
}
