<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

require_once
	dirname( __FILE__ )
	. DIRECTORY_SEPARATOR . 'fg'
	. DIRECTORY_SEPARATOR . 'Essence'
	. DIRECTORY_SEPARATOR . 'Utility'
	. DIRECTORY_SEPARATOR . 'ClassLoader.php';



/**
 *	Definitions
 */

if ( !defined( 'ESSENCE_LIB' )) {
	define( 'ESSENCE_LIB', dirname( __FILE__ ));
}

if ( !defined( 'ESSENCE_BOOTSTRAPED' )) {
	define( 'ESSENCE_BOOTSTRAPED', true );
}



/**
 *	Autoload.
 */

$ClassLoader = new fg\Essence\Utility\ClassLoader( ESSENCE_LIB );
$ClassLoader->register( );
