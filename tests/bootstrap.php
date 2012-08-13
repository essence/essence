<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

require_once 
	dirname( dirname( __FILE__ ))
	. DIRECTORY_SEPARATOR . 'lib' 
	. DIRECTORY_SEPARATOR . 'bootstrap.php';



/**
 *	Definitions
 */

if ( !defined( 'ESSENCE_TEST_ROOT')) {
	define( 'ESSENCE_TEST_ROOT', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
}

if ( !defined( 'ESSENCE_BOOTSTRAPED' )) {
	define( 'ESSENCE_BOOTSTRAPED', true );
}



/**
 *	Autoload
 */

$ClassLoader = new \Essence\ClassLoader( ESSENCE_TEST_ROOT );
$ClassLoader->register( );

// Composer

require_once
	dirname( dirname( __FILE__ ))
	. DIRECTORY_SEPARATOR . 'vendor'
	. DIRECTORY_SEPARATOR . 'autoload.php';
