<?php
/**
 * Static Web.
 *
 * @copyright    Copyright (c) 2010 Jan Tvrdík
 * @license      MIT
 * @package      StaticWeb
 */

namespace StaticWeb;

use Nette;
use Nette\Debug;
use Nette\Environment as Env;
use Nette\Web\Html;



// Load libraries
require LIBS_DIR . '/Nette/loader.php';
require APP_DIR . '/routers/StaticRouter.php';
require APP_DIR . '/classes/TemplateLocator.php';
require APP_DIR . '/classes/PresenterFactory.php';

// Enable and setup Nette\Debug
Debug::enable();
Debug::$strictMode = !Debug::$productionMode;

// Configure environment
date_default_timezone_set('Europe/Prague');
Html::$xhtml = FALSE;

// Configure application
$application = Env::getApplication();
$application->errorPresenter = 'Error';
$application->catchExceptions = Debug::$productionMode;

// Configure application context
$context = $application->getContext();
$context->addService('StaticWeb\\TemplateLocator', 'StaticWeb\\TemplateLocator');
});
$context->addService('Nette\\Application\\IPresenterFactory', function () use ($context) {
	return new PresenterFactory(Env::getVariable('appDir'), $context);
});
$context->addService('Nette\\Application\\IRouter', function() use ($context) {
	$router = new StaticRouter('StaticPage', 'homepage', 'default');
	$router->setContext($context);
	return $router;
});

// Run the application!
$application->run();
