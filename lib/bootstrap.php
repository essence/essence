<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */



/**
 *	Definitions
 */

if ( !defined( 'ESSENCE_LIB' )) {
	define( 'ESSENCE_LIB', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
}

if ( !defined( 'ESSENCE_DEFAULT_PROVIDERS' )) {
	define(
		'ESSENCE_DEFAULT_PROVIDERS',
		dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'providers.php'
	);
}
