<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette;

use Nette;



/**
 * PHP callback encapsulation.
 *
 * @author     David Grudl
 */
final class Callback extends Object
{
	/** @var callback */
	private $cb;



	/**
	 * Do not call directly, use callback() function.
	 * @param  mixed   class, object, function, callback
	 * @param  string  method
	 */
	public function __construct($t, $m = NULL)
	{
		if ($m === NULL) {
			$this->cb = $t;
		} else {
			$this->cb = array($t, $m);
		}

		if (!is_callable($this->cb, TRUE)) {
			throw new \InvalidArgumentException("Invalid callback.");
		}
	}



	/**
	 * Invokes callback. Do not call directly.
	 * @return mixed
	 */
	public function __invoke()
	{
		if (!is_callable($this->cb)) {
			throw new \InvalidStateException("Callback '$this' is not callable.");
		}
		$args = func_get_args();
		return call_user_func_array($this->cb, $args);
	}



	/**
	 * Invokes callback.
	 * @return mixed
	 */
	public function invoke()
	{
		if (!is_callable($this->cb)) {
			throw new \InvalidStateException("Callback '$this' is not callable.");
		}
		$args = func_get_args();
		return call_user_func_array($this->cb, $args);
	}



	/**
	 * Invokes callback with an array of parameters.
	 * @param  array
	 * @return mixed
	 */
	public function invokeArgs(array $args)
	{
		if (!is_callable($this->cb)) {
			throw new \InvalidStateException("Callback '$this' is not callable.");
		}
		return call_user_func_array($this->cb, $args);
	}



	/**
	 * Verifies that callback can be called.
	 * @return bool
	 */
	public function isCallable()
	{
		return is_callable($this->cb);
	}



	/**
	 * Returns PHP callback pseudotype.
	 * @return callback
	 */
	public function getNative()
	{
		return $this->cb;
	}



	/**
	 * @return bool
	 */
	public function isStatic()
	{
		return is_array($this->cb) ? is_string($this->cb[0]) : is_string($this->cb);
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		is_callable($this->cb, TRUE, $textual);
		return $textual;
	}

}
