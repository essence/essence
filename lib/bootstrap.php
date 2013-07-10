<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

require_once
	dirname( __FILE__ )
	. DIRECTORY_SEPARATOR . 'Essence'
	. DIRECTORY_SEPARATOR . 'Utility'
	. DIRECTORY_SEPARATOR . 'Autoload.php';

use Essence\Utility\Autoload;



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

Autoload::setup( ESSENCE_LIB );
