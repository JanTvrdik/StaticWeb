<?php
/**
 * Static Web.
 *
 * @copyright    Copyright (c) 2010 Jan Tvrdík
 * @license      MIT
 * @package      StaticWeb
 */

namespace StaticWeb;

use Texy;
use TexyHandlerInvocation;
use TexyHeadingModule;
use TexyHtml;
use TexyModifier;
use fshlParser;

require_once LIBS_DIR . '/texy/texy.php';



/**
 * Configured Texy!
 *
 * @author   Jan Tvrdík
 * @license  MIT
 */
class ConfiguredTexy extends Texy
{
	/**
	 * Class constructor.
	 *
	 * @author   Jan Tvrdík
	 */
	public function __construct()
	{
		parent::__construct();

		// output
		$this->setOutputMode(self::HTML5);
		$this->htmlOutputModule->removeOptional = FALSE;
		$this->tabWidth = 4;
		self::$advertisingNotice = FALSE;

		// tags, styles, classes
		$this->allowedTags = self::ALL;
		$this->allowedClasses = self::ALL;
		$this->allowedStyles = self::ALL;

		// headings
		$this->headingModule->balancing = TexyHeadingModule::DYNAMIC;
		$this->headingModule->top = 2;

		// phrases
		$this->allowed['phrase/ins'] = TRUE;               // ++inserted++
		$this->allowed['phrase/del'] = TRUE;               // --deleted--
		$this->allowed['phrase/sup'] = TRUE;               // ^^superscript^^
		$this->allowed['phrase/sub'] = TRUE;               // __subscript__
		$this->allowed['phrase/cite'] = FALSE;             // ~~cite~~
		$this->allowed['deprecated/codeswitch'] = FALSE;   // `=code

		// blocks
		$this->allowed['blocks'] = TRUE;
		$this->allowed['blocks/code'] = TRUE;
		$this->allowed['blocks/php'] = TRUE;

		// handlers
		$this->addHandler('block', array($this, 'codeBlockHandler'));
		$this->addHandler('newReference', array($this, 'referenceHandler'));
	}



	/**
	 * Handler for code block
	 *
	 * @author   Jan Tvrdík
	 * @param    TexyHandlerInvocation
	 * @param    string
	 * @param    string
	 * @param    string|NULL
	 * @param    TexyModifier
	 * @return   TexyHtml
	 */
	public function codeBlockHandler(TexyHandlerInvocation $invocation, $blockType, $content, $lang, TexyModifier $modifier)
	{
		if (substr($blockType, 0, 6) !== 'block/') return $invocation->proceed();
		$blockType = strtolower(substr($blockType, 6));
		if ($lang === NULL && in_array($blockType, array('php', 'js', 'html'))) {
			$lang = $blockType;
		} elseif ($blockType !== 'code') {
			return $invocation->proceed();
		}

		require_once LIBS_DIR . '/fshl/fshl.php';

		$fshlLang = strtoupper($lang);

		$fshl = new fshlParser('HTML_UTF8', P_TAB_INDENT, 4);
		if (!$fshl->isLanguage($fshlLang)) {
			return $invocation->proceed();
		}
		$texy = $invocation->getTexy();
		$content = Texy::outdent($content);
		$content = $fshl->highlightString($fshlLang, $content);
		$content = $texy->protect($content, Texy::CONTENT_BLOCK);

		$elPre = TexyHtml::el('pre');
		if ($modifier) $modifier->decorate($texy, $elPre);
		$elPre->attrs['class'] = $lang;

		$elCode = $elPre->create('code', $content);

		return $elPre;
	}



	/**
	 * Handler for link via reference syntax
	 *
	 * @todo     Refactoring!
	 *
	 * @author   Jan Tvrdík
	 * @param    TexyHandlerInvocation
	 * @param    string
	 * @return   TexyHtml
	 */
	public function referenceHandler(TexyHandlerInvocation $invocation, $s)
	{
		list ($a, $b) = explode('|', $s);
		$a = trim($a);
		$b = trim($b);

		return TexyHtml::el('a', $a)->href($b);
	}

}
