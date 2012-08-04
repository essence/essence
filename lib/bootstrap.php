<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

require_once 'Essence/ClassLoader.php';



/**
 *	Definitions
 */

if ( !defined( 'ESSENCE_ROOT' )) {
	define( 'ESSENCE_ROOT', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
}



/**
 *	Autoload.
 */

$classLoader = new Essence\ClassLoader( ESSENCE_ROOT );
$classLoader->register( );
