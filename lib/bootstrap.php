<?php

require_once 'Essence/ClassLoader.php';

if ( !defined( 'ESSENCE_ROOT' )) {
	define( 'ESSENCE_ROOT', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
}

$classLoader = new Essence\ClassLoader( ESSENCE_ROOT );
$classLoader->register( );
