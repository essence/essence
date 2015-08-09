<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Media;
use Parkour\Traverse;
use Parkour\Functor\Execute;



/**
 *	Base class for a Provider.
 */
abstract class Provider {

	/**
	 *	Preparators.
	 *
	 *	@var array
	 */
	protected $_preparators = [];



	/**
	 *	Presenters.
	 *
	 *	@var array
	 */
	protected $_presenters = [];



	/**
	 *	Configuration options.
	 *
	 *	@var array
	 */
	protected $_properties = [];



	/**
	 *	Sets preparators.
	 *
	 *	@param array $preparators Preparators.
	 */
	public function setPreparators(array $preparators) {
		$this->_preparators = $preparators;
	}



	/**
	 *	Sets presenters.
	 *
	 *	@param array $presenters Presenters.
	 */
	public function setPresenters(array $presenters) {
		$this->_presenters = $presenters;
	}



	/**
	 *	Extracts information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media Embed informations.
	 */
	final public function extract($url, array $options = []) {
		$url = $this->filter($url, $this->_preparators);

		$Media = $this->_extract($url, $options);
		$Media->setDefault('url', $url);

		return $this->filter($Media, $this->_presenters);
	}



	/**
	 *	Filters a value through a set of functions.
	 *
	 *	@param mixed $value Value.
	 *	@param array $filters Filters.
	 *	@return mixed Filtered value.
	 */
	private function filter($value, array $filters) {
		return Traverse::reduce($filters, new Execute(), $value);
	}



	/**
	 *	Does the actual fetching of informations.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media Embed informations.
	 *	@throws Exception
	 */
	abstract protected function _extract($url, array $options);

}
