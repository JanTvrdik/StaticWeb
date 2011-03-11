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
use Nette\Environment as Env;
use Nette\Application\PresenterRequest;
use Nette\Web\IHttpRequest;
use Nette\Web\Uri;



/**
 * Presenter loader
 *
 * @author   Jan Tvrdík
 */
class PresenterLoader extends Nette\Application\PresenterLoader
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
