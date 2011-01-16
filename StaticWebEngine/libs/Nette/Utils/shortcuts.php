<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

// no namespace



/**
 * Callback factory.
 * @param  mixed   class, object, function, callback
 * @param  string  method
 * @return Callback
 */
function callback($callback, $m = NULL)
{
	return ($m === NULL && $callback instanceof Callback) ? $callback : new Callback($callback, $m);
}



/**
 * Debug::dump shortcut.
 */
function dump($var)
{
	foreach (func_get_args() as $arg) Debug::dump($arg);
	return $var;
}
