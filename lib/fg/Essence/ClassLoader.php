<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	A simple PSR-0 compliant class loader.
 *
 *	@package fg.Essence
 */

class ClassLoader {

	/**
	 *	Base include path for all class files.
	 *
	 *	@var array
	 */

	protected $_basePath = '';



	/**
	 *	Constructor.
	 *
	 *	@param string $basePath Base include path for all class files.
	 */

	public function __construct( $basePath = '' ) {

		$this->_basePath = rtrim( $basePath, DIRECTORY_SEPARATOR );
	}



	/**
	 *	Registers this class loader on the SPL autoload stack.
	 */

	public function register( ) {

		spl_autoload_register( array( $this, 'load' ));
	}



	/**
	 *  Loads the given class or interface.
	 *
	 *  @param string $className Name of the class to load.
	 */

	public function load( $className ) {

		$path = $this->_basePath
			. DIRECTORY_SEPARATOR
			. str_replace( '\\', DIRECTORY_SEPARATOR, $className )
			. '.php';

		if ( file_exists( $path )) {
			require_once $path;
		}
	}
}
