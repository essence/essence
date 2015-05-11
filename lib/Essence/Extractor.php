<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence;

use Essence\Provider\Collection;
use Essence\Utility\Hash;
use Essence\Exception;
use Exception as NativeException;



/**
 *	Extracts informations about web pages.
 */
class Extractor {

	/**
	 *	A collection of providers.
	 *
	 *	@var Collection
	 */
	protected $_Collection = null;



	/**
	 *	Constructor.
	 *
	 *	@param Collection $Collection Providers collection.
	 */
	public function __construct(Collection $Collection) {
		$this->_Collection = $Collection;
	}



	/**
	 *	Fetches informations about the given URL.
	 *
	 *	@todo Error reporting.
	 *	@param string $url URL to fetch informations from.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return Essence\Media Embed informations.
	 */
	public function extract($url, array $options = []) {
		$providers = $this->_Collection->providers($url);

		foreach ($providers as $Provider) {
			try {
				return $Provider->embed($url, $options);
			} catch (Exception $Exception) {
				// ...
			} catch (NativeException $Exception) {
				// ...
			}
		}

		return null;
	}



	/**
	 *	Fetches informations about the given URLs.
	 *
	 *	@param array $urls An array of URLs to fetch informations from.
	 *	@param array $options Custom options to be interpreted by a provider.
	 *	@return array An array of informations, indexed by URL.
	 */
	public function extractAll(array $urls, array $options = []) {
		return Hash::combine($urls, function($url) use ($options) {
			yield $url => $this->extract($url, $options);
		});
	}
}
