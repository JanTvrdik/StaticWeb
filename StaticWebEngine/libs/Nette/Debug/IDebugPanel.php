<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette
 */



/**
 * Custom output for Debug.
 *
 * @author     David Grudl
 */
interface IDebugPanel
{

	/**
	 * Renders HTML code for custom tab.
	 * @return void
	 */
	function getTab();

	/**
	 * Renders HTML code for custom panel.
	 * @return void
	 */
	function getPanel();

	/**
	 * Returns panel ID.
	 * @return string
	 */
	function getId();

}