<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Application
 */



/**
 * Routing debugger for Debug Bar.
 *
 * @author     David Grudl
 */
class RoutingDebugger extends DebugPanel
{
	/** @var IRouter */
	private $router;

	/** @var IHttpRequest */
	private $httpRequest;

	/** @var ArrayObject */
	private $routers;

	/** @var PresenterRequest */
	private $request;



	public function __construct(IRouter $router, IHttpRequest $httpRequest)
	{
		$this->router = $router;
		$this->httpRequest = $httpRequest;
		$this->routers = new ArrayObject;
		parent::__construct('RoutingDebugger', array($this, 'renderTab'), array($this, 'renderPanel'));
	}



	/**
	 * Renders debuger tab.
	 * @return void
	 */
	public function renderTab()
	{
		$this->analyse($this->router);
		require dirname(__FILE__) . '/templates/RoutingDebugger.tab.phtml';
	}



	/**
	 * Renders debuger panel.
	 * @return void
	 */
	public function renderPanel()
	{
		require dirname(__FILE__) . '/templates/RoutingDebugger.panel.phtml';
	}



	/**
	 * Analyses simple route.
	 * @param  IRouter
	 * @return void
	 */
	private function analyse($router)
	{
		if ($router instanceof MultiRouter) {
			foreach ($router as $subRouter) {
				$this->analyse($subRouter);
			}
			return;
		}

		$request = $router->match($this->httpRequest);
		$matched = $request === NULL ? 'no' : 'may';
		if ($request !== NULL && empty($this->request)) {
			$this->request = $request;
			$matched = 'yes';
		}

		$this->routers[] = array(
			'matched' => $matched,
			'class' => get_class($router),
			'defaults' => $router instanceof Route || $router instanceof SimpleRouter ? $router->getDefaults() : array(),
			'mask' => $router instanceof Route ? $router->getMask() : NULL,
			'request' => $request,
		);
	}

}
