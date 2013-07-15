<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

use Essence\Utility\Autoload;

require_once dirname( __FILE__ )
	. DIRECTORY_SEPARATOR . 'Essence'
	. DIRECTORY_SEPARATOR . 'Utility'
	. DIRECTORY_SEPARATOR . 'Autoload.php';



/**
 *	Definitions
 */

if ( !defined( 'ESSENCE_LIB' )) {
	define( 'ESSENCE_LIB', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
}

if ( !defined( 'ESSENCE_DEFAULT_CONFIG' )) {
	define( 'ESSENCE_DEFAULT_CONFIG', ESSENCE_LIB . 'config.php' );
}



/**
 *	Autoload.
 */

Autoload::setup( ESSENCE_LIB );
