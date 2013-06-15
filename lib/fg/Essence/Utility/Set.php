<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence\Utility;



/**
 *	An utility class to manipulate data sets.
 *
 *	@package fg.Essence.Utility
 */

class Set {

	/**
	 *	Reindexes an array, according to the given correspondances.
	 *
	 *	@param array $data The data to be reindexed.
	 *	@param array $correspondances An array of index correspondances of the
	 *		form `array( 'currentIndex' => 'newIndex' )`.
	 */

	public static function reindex( array $data, array $correspondances ) {

		$result = $data;

		foreach ( $correspondances as $from => $to ) {
			if ( isset( $data[ $from ])) {
				$result[ $to ] = $data[ $from ];
			}
		}

		return $result;
	}
}
