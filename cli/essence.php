#!/usr/bin/php -q
<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
require_once dirname(dirname(__FILE__))
	. DIRECTORY_SEPARATOR . 'vendor'
	. DIRECTORY_SEPARATOR . 'autoload.php';



/**
 *
 */
if ($argc < 3) {
	echo "Too few arguments.\n";
} else {
	main($argv[ 1 ], $argv[ 2 ]);
}



/**
 *
 */
function main($method, $url) {
	$Essence = new Essence\Essence();

	switch ($method) {
		case 'embed':
			echo dumpMedia($Essence->embed($url));
			break;

		case 'extract':
			echo dumpArray($Essence->extract($url));
			break;
	}
}



/**
 *
 */
function dumpMedia($Media) {
	if (!$Media) {
		return "No results.\n";
	}

	return dumpArray(array_filter($Media->properties()));
}



/**
 *
 */
function dumpArray(array $data) {
	if (empty($data)) {
		return "No results.\n";
	}

	$length = maxKeyLength($data);
	$output = '';

	foreach ($data as $key => $value) {
		$output .= sprintf("%{$length}s: %s\n", $key, $value);
	}

	return $output;
}



/**
 *
 */
function maxKeyLength($data) {
	$keys = array_keys($data);
	$lengths = array_map('strlen', $keys);

	return max($lengths);
}
