<?php

/**
 * Nette Framework (version 2.0-dev ad9cf6a released on 2011-01-13)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */



/**
 * Check and reset PHP configuration.
 */
if (!defined('PHP_VERSION_ID')) {
	$tmp = explode('.', PHP_VERSION);
	define('PHP_VERSION_ID', ($tmp[0] * 10000 + $tmp[1] * 100 + $tmp[2]));
}

if (PHP_VERSION_ID < 50200) {
	throw new Exception('Nette Framework requires PHP 5.2.0 or newer.');
}

error_reporting(E_ALL | E_STRICT);
@set_magic_quotes_runtime(FALSE); // @ - deprecated since PHP 5.3.0
iconv_set_encoding('internal_encoding', 'UTF-8');
extension_loaded('mbstring') && mb_internal_encoding('UTF-8');
@header('X-Powered-By: Nette Framework'); // @ - headers may be sent



/**
 * Load and configure Nette Framework
 */
define('NETTE', TRUE);
define('NETTE_DIR', dirname(__FILE__));
define('NETTE_VERSION_ID', 20000); // v2.0.0
define('NETTE_PACKAGE', 'PHP 5.2');



require_once dirname(__FILE__) . '/Utils/shortcuts.php';
require_once dirname(__FILE__) . '/Utils/exceptions.php';
require_once dirname(__FILE__) . '/Utils/Object.php';
require_once dirname(__FILE__) . '/Loaders/LimitedScope.php';
require_once dirname(__FILE__) . '/Loaders/AutoLoader.php';
require_once dirname(__FILE__) . '/Loaders/NetteLoader.php';


NetteLoader::getInstance()->register();
