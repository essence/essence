<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

namespace fg\Essence;

require_once dirname( dirname( __FILE__ ))
	. DIRECTORY_SEPARATOR . 'lib'
	. DIRECTORY_SEPARATOR . 'bootstrap.php';



/**
 *	Definitions
 */

if ( !defined( 'ESSENCE_TEST' )) {
	define( 'ESSENCE_TEST', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
}

if ( !defined( 'ESSENCE_RESOURCES' )) {
	define( 'ESSENCE_RESOURCES', ESSENCE_TEST . 'resources' . DIRECTORY_SEPARATOR );
}

if ( !defined( 'ESSENCE_HTTP' )) {
	define( 'ESSENCE_HTTP', ESSENCE_RESOURCES . 'http' . DIRECTORY_SEPARATOR );
}



/**
 *	Autoload
 */

$ClassLoader = new ClassLoader( ESSENCE_TEST );
$ClassLoader->register( );



/**
 *	Disable cURL to facilitate testing.
 */

Http::$curl = false;
