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

require_once __DIR__ . '/BaseManager.php';



/**
 * Page manager
 *
 * @author   Jan Tvrdík
 */
class PageManager extends BaseManager
{
	/**
	 * Checks whether page exists.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @param    string
	 * @return   bool
	 */
	public function exists($page, $lang = NULL)
	{
		return $this->getTemplateLocator()->existsPage($page, $lang);
	}



	/**
	 * Returns list of available languages.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   array             # => langCode
	 */
	public function getAvailableLanguages($page)
	{
		return $this->getTemplateLocator()->getAvailableLanguages($page);
	}



	/**
	 * Returns TemplateLocator instance.
	 *
	 * @author   Jan Tvrdík
	 * @return   TemplateLocator
	 */
	private function getTemplateLocator()
	{
		return $this->getContext()->getService('StaticWeb\\TemplateLocator');
	}

}
