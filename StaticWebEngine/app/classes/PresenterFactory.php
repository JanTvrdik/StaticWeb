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
 * Presenter factory
 *
 * @author   Jan Tvrdík
 */
class PresenterFactory extends Nette\Application\PresenterFactory
{
	/**
	 * Formats presenter class name from its name.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   string
	 */
	public function formatPresenterClass($presenter)
	{
		return __NAMESPACE__ . '\\' . parent::formatPresenterClass($presenter);
	}



	/**
	 * Formats presenter name from class name.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   string
	 */
	public function unformatPresenterClass($class)
	{
		return parent::unformatPresenterClass(substr($class, strlen(__NAMESPACE__) + 1));
	}

}
