<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Mixin\Configurable;
use Essence\Exception;
use Essence\Media;
use Cascade\Cascade;



/**
 *	Base class for a Provider.
 */
abstract class Provider {

	use Configurable;



	/**
	 *	Preparators.
	 *
	 *	@var Cascade
	 */
	protected $_Preparators = null;



	/**
	 *	Presenters.
	 *
	 *	@var Cascade
	 */
	protected $_Presenters = null;



	/**
	 *	Configuration options.
	 *
	 *	@var array
	 */
	protected $_properties = [];



	/**
	 *	Constructor.
	 */
	public function __construct() {
		$this->_Preparators = new Cascade();
		$this->_Presenters = new Cascade();
	}



	/**
	 *
	 *
	 *	@param array $preparators Preparators.
	 */
	public function setPreparators(array $preparators) {
		$this->_Preparators->setFilters($preparators);
	}



	/**
	 *
	 *
	 *	@param array $presenters Presenters.
	 */
	public function setPresenters(array $presenters) {
		$this->_Presenters->setFilters($presenters);
	}



	/**
	 *	Fetches embed information from the given URL.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media|null Embed informations, or null if nothing could be
	 *		fetched.
	 */
	public final function embed($url, array $options = []) {
		$this->_Preparators->filter($url);

		$Media = $this->_embed($url, $options);
		$Media->setDefault('url', $url);

		return $this->_Presenters->filter($Media);
	}



	/**
	 *	Does the actual fetching of informations.
	 *
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by the provider.
	 *	@return Media Embed informations.
	 *	@throws Essence\Exception
	 */
	abstract protected function _embed($url, array $options);

}
