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



/**
 * Base class for all managers.
 *
 * @author   Jan Tvrdík
 */
abstract class BaseManager extends Nette\Object
{
	/** @var     Nette\IContext */
	private $context;



	/**
	 * Returns current context.
	 *
	 * @author   Jan Tvrdík
	 * @return   Nette\IContext
	 */
	final public function getContext()
	{
		return $this->context;
	}



	/**
	 * Sets current context.
	 *
	 * @author   Jan Tvrdík
	 * @param    Nette\IContext
	 * @return   void
	 */
	public function setContext(Nette\IContext $context)
	{
		$this->context = $context;
	}

}
