<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;



/**
 *	Represents a package.
 *
 *	@package fg.Essence
 */

class Package {

	/**
	 *	Root path to the package.
	 *
	 *	@var string
	 */

	protected $_path = '';



	/**
	 *	Packages separator.
	 *
	 *	@var string
	 */

	protected $_separator = '';



	/**
	 *	Constructs a package located at the given path.
	 *
	 *	@param string $path Root path to the package.
	 *	@param string $separator Packages separator.
	 */

	public function __construct( $path, $separator = '\\' ) {

		$this->_path = is_dir( $path )
			? rtrim( $path, DIRECTORY_SEPARATOR )
			: dirname( $path );

		$this->_separator = $separator;
	}



	/**
	 *	Returns the root path to the package.
	 *
	 *	@return string Path.
	 */

	public function path( ) {

		return $this->_path;
	}



	/**
	 *	Returns the package separator.
	 *
	 *	@return string Package separator.
	 */

	public function separator( ) {

		return $this->_separator;
	}



	/**
	 *	Scans the directory and returns the classes it contains.
	 *
	 *	@note This method doesn't deal with symlinks.
	 *	@param boolean $recursive Whether or not to search recursively.
	 *	@param array $packages Sub packages in which to search for, relatively
	 *		to the base package path.
	 *	@return array An array of directory and/or file paths.
	 */

	public function classes( array $packages = array( ), $recursive = false ) {

		$classes = array( );
		$searchPath = empty( $packages )
			? $this->_path
			: $this->_path . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $packages );

		$entries = scandir( $searchPath );

		if ( is_array( $entries )) {
			foreach ( $entries as $entry ) {
				$path = $searchPath . DIRECTORY_SEPARATOR . $entry;
				$parts = $packages;
				$parts[ ] = basename( $entry, '.php' );

				if (
					$recursive
					&& is_dir( $path )
					&& ( $entry != '.' )
					&& ( $entry != '..' )
				) {
					$classes = array_merge(
						$classes,
						$this->classes( $parts, $recursive )
					);
				}

				if ( is_file( $path )) {
					$classes[ ] = implode( $this->_separator, $parts );
				}
			}
		}

		return $classes;
	}
}
