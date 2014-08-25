<?php

/**
 *	@author Félix Girault <felix.girault@gmail.com>
 *	@license FreeBSD License (http://opensource.org/licenses/BSD-2-Clause)
 */
require_once dirname(dirname(__FILE__))
	. DIRECTORY_SEPARATOR . 'vendor'
	. DIRECTORY_SEPARATOR . 'autoload.php';



/**
 *	Definitions
 */
defined('ESSENCE_TEST')
or define('ESSENCE_TEST', dirname(__FILE__) . DIRECTORY_SEPARATOR);

defined('ESSENCE_HTTP')
or define('ESSENCE_HTTP', ESSENCE_TEST . 'http' . DIRECTORY_SEPARATOR);
