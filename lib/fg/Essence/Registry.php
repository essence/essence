<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	An object registry.
 *
 *	@package fg.Essence
 */

class Registry {

	/**
	 *	Collection of objects.
	 *
	 *	@var array
	 */

	protected static $_objects = array( );



	/**
	 *	Tells if an object associated to the given name is registered.
	 *
	 *	@param string $name Object name.
	 *	@return boolean Whether the object is registered.
	 */

	public static function has( $name ) {

		return isset( self::$_objects[ $name ]);
	}



	/**
	 *	Returns the object associated to the given name.
	 *
	 *	@param string $name Object name.
	 *	@return object The registered object.
	 *	@throws \fg\Essence\Exception
	 */

	public static function get( $name ) {

		if ( !self::has( $name )) {
			throw new Exception(
				"There is no registered object named '$name'."
			);
		}

		return self::$_objects[ $name ];
	}



	/**
	 *	Registers an object under the given name.
	 *
	 *	@param string $name Object name.
	 *	@param object $Object Object instance.
	 */

	public static function register( $name, $Object ) {

		self::$_objects[ $name ] = $Object;
	}
}
