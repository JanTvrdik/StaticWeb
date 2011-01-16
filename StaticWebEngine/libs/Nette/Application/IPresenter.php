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
 * Defines method that must be implemented to allow a component to act like a presenter.
 *
 * @author     David Grudl
 */
interface IPresenter
{

	/**
	 * @param  PresenterRequest
	 * @return IPresenterResponse
	 */
	function run(PresenterRequest $request);

}
