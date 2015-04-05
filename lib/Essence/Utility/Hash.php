<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
namespace Essence\Utility;

use Closure;



/**
 *	An utility class to manipulate data sets.
 */
class Hash {

	/**
	 *	Reindexes an array, according to the given correspondances.
	 *
	 *	@param array $data The data to be reindexed.
	 *	@param array $correspondances An array of index correspondances of the
	 *		form `['currentIndex' => 'newIndex']`.
	 *	@return array Reindexed array.
	 */
	public static function reindex(array $data, array $correspondances) {
		$result = $data;

		foreach ($correspondances as $from => $to) {
			if (isset($data[$from])) {
				$result[$to] = $data[$from];
			}
		}

		return $result;
	}



	/**
	 *	Indexes an array depending on the values it contains.
	 *
	 *	@param array $data Data.
	 *	@param Closure $generator Generator function that yields keys and values.
	 *	@return array Data.
	 */
	public static function combine(array $data, Closure $generator) {
		$indexed = [];

		foreach ($data as $key => $value) {
			$Indexer = $generator($value, $key);
			$indexed[$Indexer->key()] = $Indexer->current();
		}

		return $indexed;
	}
}
