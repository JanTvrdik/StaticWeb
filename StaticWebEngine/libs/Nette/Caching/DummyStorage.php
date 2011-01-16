<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Caching
 */



/**
 * Cache dummy storage.
 *
 * @author     David Grudl
 */
class DummyStorage extends Object implements ICacheStorage
{

	/**
	 * Read from cache.
	 * @param  string key
	 * @return mixed|NULL
	 */
	public function read($key)
	{
	}



	/**
	 * Writes item into the cache.
	 * @param  string key
	 * @param  mixed  data
	 * @param  array  dependencies
	 * @return void
	 */
	public function write($key, $data, array $dp)
	{
	}



	/**
	 * Removes item from the cache.
	 * @param  string key
	 * @return void
	 */
	public function remove($key)
	{
	}



	/**
	 * Removes items from the cache by conditions & garbage collector.
	 * @param  array  conditions
	 * @return void
	 */
	public function clean(array $conds)
	{
	}

}
