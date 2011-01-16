<?php
/**
 * Static Web.
 *
 * @copyright    Copyright (c) 2010 Jan Tvrdík
 * @license      MIT
 * @package      StaticWeb
 */

// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/StaticWebEngine/app');

// absolute filesystem path to the libraries
define('LIBS_DIR', WWW_DIR . '/StaticWebEngine/libs');

// absolute filesystem path to the temporary files
define('TEMP_DIR', WWW_DIR . '/StaticWebEngine/temp');

// absolute filesystem path to templates
define('TEMPLATES_DIR', WWW_DIR . '/templates');

// load bootstrap file
require APP_DIR . '/bootstrap.php';
