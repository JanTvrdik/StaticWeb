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
 * The Nette Framework.
 *
 * @author     David Grudl
 */
final class Framework
{

	/**#@+ Nette Framework version identification */
	const NAME = 'Nette Framework';

	const VERSION = '2.0-dev';

	const REVISION = 'ad9cf6a released on 2011-01-13';
	/**#@-*/

	/** @var bool set to TRUE if your host has disabled function ini_set */
	public static $iAmUsingBadHost = FALSE;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new LogicException("Cannot instantiate static class " . get_class($this));
	}

}

class NClosureFix
{
	static $vars = array();

	static function uses($args)
	{
		self::$vars[] = $args;
		return count(self::$vars)-1;
	}
}