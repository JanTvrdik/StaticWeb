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
	 * @param    string            language code (ISO 639-1)
	 * @return   string
	 */
	public function getTemplatePath($page, $lang = NULL)
	{
		return TEMPLATES_DIR . "/$page" . ($lang ? ".$lang" : '') . '.latte';
	}



	/**
	 * Returns absolute filesystem path to layout or FALSE if layout does not exist.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @param    string            language code (ISO 639-1)
	 * @return   string|FALSE      FALSE means "not found"
	 */
	public function getLayoutPath($page, $lang = NULL)
	{
		$dir = $page;
		$fileName1 = '@layout' . ($lang ? ".$lang" : '') . '.latte';
		$fileName2 = '@layout.latte';
		do {
			$dir = substr($dir, 0, strrpos($dir, '/'));
			$prefix = TEMPLATES_DIR . '/' . ($dir ? $dir . '/' : '');
			$path = $prefix . $fileName1;
			if (is_file($path = $prefix . $fileName1)) return $path;
			elseif (is_file($path = $prefix . $fileName2)) return $path;
			elseif (empty($dir)) return FALSE;
		} while (TRUE);
	}



	/**
	 * Checks for the existence of the page.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @param    string            language code (ISO 639-1)
	 * @return   bool              TRUE = page exists, FALSE = page does not exist
	 */
	public function existsPage($page, $lang = NULL)
	{
		return is_file($this->getTemplatePath($page, $lang));
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
		$langs = array();
		$templates = Nette\Finder::findFiles("$page.*.latte")->in(TEMPLATES_DIR);
		foreach ($templates as $template) {
			$langs[] = substr($template, -8, 2);
		}
		return $langs;
	}

}
