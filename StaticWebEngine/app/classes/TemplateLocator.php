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
 * Template locator
 *
 * @author   Jan Tvrdík
 */
class TemplateLocator extends Nette\Object
{
	/**
	 * Returns absolute filesystem path to template.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   string
	 */
	public function getTemplatePath($page)
	{
		return TEMPLATES_DIR . '/' . $page . '.latte';
	}



	/**
	 * Returns absolute filesystem path to layout or FALSE if layout does not exist.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   string|FALSE
	 */
	public function getLayoutPath($page)
	{
		$dir = $page;
		do {
			$dir = substr($dir, 0, strrpos($dir, '/'));
			$path = TEMPLATES_DIR . '/' . ($dir ? $dir . '/' : '') . '@layout.latte';
			if (is_file($path)) return $path;
			elseif (empty($dir)) return FALSE;
		} while (TRUE);
	}



	/**
	 * Checks for the existence of the page.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   bool              TRUE = page exists, FALSE = page does not exist
	 */
	public function existsPage($page)
	{
		return is_file($this->getTemplatePath($page));
	}

}
