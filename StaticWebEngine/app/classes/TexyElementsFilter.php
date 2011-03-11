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
use Texy;



/**
 * Template filter for processing <texy>...</texy> elements
 *
 * @author   Jan Tvrdík
 */
class TexyElementsFilter extends Nette\Object
{
	/** @var     Texy              configured Texy! instance */
	public $texy;

	/** @var     bool              enable automatic Latte syntax change inside <texy> elements */
	public $autoChangeSyntax = FALSE;



	/**
	 * Processes <texy>...</texy> elements.
	 *
	 * @author   David Grudl, Jan Tvrdík
	 * @param    string
	 * @return   string
	 */
	public function __invoke($s)
	{
		$texy = $this->texy;
		$autoChangeSyntax = $this->autoChangeSyntax;
		if ($texy === NULL) throw new \InvalidStateException(get_class($this) . '::$texy must be set.');

		return Nette\String::replace($s, '#<texy>(.*?)</texy>#s', function ($m) use ($texy, $autoChangeSyntax) {
				$s = $m[1];
				$singleLine = (strpos($s, "\n") === FALSE);
				$s = trim($s, "\r\n");
				$tabs = strspn($s, "\t");
				if ($tabs) $s = Nette\String::replace($s, "#^\t{1,$tabs}#m", '');
				$s = $texy->process($s, $singleLine);
				return ($autoChangeSyntax ? "{syntax double}$s{{/syntax}}" : $s);
			}
		);
	}

}
