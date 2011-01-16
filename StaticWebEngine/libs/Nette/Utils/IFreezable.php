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
 * Object that has a modifiable and a read-only (frozen) state.
 *
 * @author     David Grudl
 */
interface IFreezable
{

	/**
	 * Makes the object unmodifiable.
	 * @return void
	 */
	function freeze();

	/**
	 * Is the object unmodifiable?
	 * @return bool
	 */
	function isFrozen();

}
