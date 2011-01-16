<?php
/**
 * Static Web.
 *
 * @copyright    Copyright (c) 2010 Jan Tvrdík
 * @license      MIT
 * @package      StaticWeb
 */

// Load libraries
require LIBS_DIR . '/Nette/loader.php';
require APP_DIR . '/routers/StaticRouter.php';

// Enable and setup Nette\Debug
Debug::enable();
Debug::$strictMode = !Debug::$productionMode;

// Configure environment
date_default_timezone_set('Europe/Prague');
Html::$xhtml = FALSE;

// Configure application
$application = Environment::getApplication();
$application->errorPresenter = 'Error';
$application->catchExceptions = Debug::$productionMode;
$application->setRouter(new StaticRouter('StaticPage', 'homepage', 'default'));

// Run the application!
$application->run();
