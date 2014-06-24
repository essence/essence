<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */

if ( !defined( 'ESSENCE_DEFAULT_PROVIDERS' )) {
	define(
		'ESSENCE_DEFAULT_PROVIDERS',
		dirname( dirname( __FILE__ ))
		. DIRECTORY_SEPARATOR . 'config'
		. DIRECTORY_SEPARATOR . 'providers.json'
	);
}
