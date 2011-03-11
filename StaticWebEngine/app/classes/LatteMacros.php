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
use Nette\Templates\LatteException;



/**
 * Customized macro handler for LatteFilter
 *
 * @author   Jan Tvrdík
 */
class LatteMacros extends Nette\Templates\LatteMacros
{
	/**
	 * {pageLink ...}
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   string
	 */
	public function macroPageLink($page)
	{
		return '$presenter->link(' . $this->formatString($page) . ')';
	}

}
