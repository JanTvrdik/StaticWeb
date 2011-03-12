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
use Nette\Application\PresenterRequest;
use Nette\Web\IHttpRequest;
use Nette\Web\Uri;



/**
 * Router for static pages.
 *
 * @author   Jan Tvrdík
 */
class StaticRouter extends Nette\Object implements Nette\Application\IRouter
{
	/** @var     string            presenter name */
	private $presenter;

	/** @var     string            page used as homepage */
	private $homepage;

	/** @var     string            default page for (sub)categories */
	private $defaultPage;

	/** @var     string            default language */
	private $defaultLanguage;

	/** @var     Nette\IContext */
	private $context;

	/** @var     Nette\Web\HttpRequest */
	private $httpRequest;



	/**
	 * Class constructor
	 *
	 * @author   Jan Tvrdík
	 * @param    string            presenter name
	 * @param    string            page used as homepage
	 * @param    string            default page for (sub)categories
	 * @param    string            default language code
	 */
	public function __construct($presenter, $homepage, $defaultPage, $defaultLanguage = NULL)
	{
		$this->presenter = $presenter;
		$this->homepage = $homepage;
		$this->defaultPage = $defaultPage;
		$this->defaultLanguage = $defaultLanguage;
	}



	/**
	 * Maps HTTP request to a PresenterRequest object.
	 *
	 * @author   Jan Tvrdík
	 * @param    IHttpRequest
	 * @return   PresenterRequest|NULL
	 */
	public function match(IHttpRequest $httpRequest)
	{
		$this->httpRequest = $httpRequest;
		$path = rtrim($httpRequest->getUri()->getRelativeUri(), '/');
		$tmp = $this->resolvePath($path);
		if ($tmp === FALSE) return NULL;
		list($page, $lang) = $tmp;

		return new PresenterRequest(
			$this->presenter,
			$httpRequest->getMethod(),
			array('page' => $page, 'lang' => $lang),
			$httpRequest->getPost(),
			$httpRequest->getFiles(),
			array('secured' => $httpRequest->isSecured())
		);
	}



	/**
	 * Constructs absolute URL from PresenterRequest object.
	 *
	 * @author   Jan Tvrdík
	 * @param    PresenterRequest
	 * @param    Uri
	 * @return   string|NULL       absolute URI or NULL
	 */
	public function constructUrl(PresenterRequest $appRequest, Uri $refUri)
	{
		if ($appRequest->getPresenterName() !== $this->presenter) return NULL;
		$params = $appRequest->getParams();
		if (!isset($params['page'])) return NULL;
		$page = $params['page'];

		if ($page === $this->homepage) {
			$page = '';
		} elseif (substr($page, ($pos = strrpos($page, '/') + 1)) === $this->defaultPage) {
			$page = substr($page, 0, $pos);
		}

		return $refUri->getBaseUri() . (isset($params['lang']) ? "$params[lang]/" : '') . $page;
	}



	 /**
	  * Resolves given path and determines the right page and language.
	  *
	  * @author   Jan Tvrdík
	  * @param    string           path (without leading and trailing slashes)
	  * @param    string           language code
	  * @return   array|FALSE      0 => page, 1 => lang or FALSE (page not found)
	  */
	private function resolvePath($path, $lang = NULL)
	{
		if ($path === '') $path = $this->homepage;
		$pm = $this->getPageManager();

		if ($pm->exists($path, $lang)) {
			$page = $path;

		} elseif ($pm->exists($tmp = "$path/$this->defaultPage", $lang)) {
			$page = $tmp;

		} elseif ($lang === NULL && ((strlen($path) > 3 && $path[2] === '/') || (strlen($path) === 2 && $path[1] !== '/'))) {
			if ($tmp = $this->resolvePath(strval(substr($path, 3)), substr($path, 0, 2))) {
				return $tmp;
			}

		} elseif ($this->defaultLanguage !== NULL) {
			if ($tmp = $this->getPreferredLanguageVersion($path)) {
				$lang = $tmp;
				$page = $path;

			} elseif ($tmp = $this->getPreferredLanguageVersion($tmp2 = "$path/$this->defaultPage")) {
				$lang = $tmp;
				$page = $tmp2;
			}
		}

		return (isset($page) ? array($page, $lang) : FALSE);
	}



	/**
	 * Returns the best language version of given page for current user.
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   string|FALSE      language code or FALSE (no suitable version available)
	 */
	private function getPreferredLanguageVersion($page)
	{
		$available = $this->getPageManager()->getAvailableLanguages($page);
		$detected = $this->httpRequest->detectLanguage($available);
		if ($detected === NULL) {
			if (in_array($this->defaultLanguage, $available)) {
				return $this->defaultLanguage;
			} else {
				return FALSE;
			}

		} else {
			return $detected;
		}
	}



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



	/**
	 * Returns page manager.
	 *
	 * @author   Jan Tvrdík
	 * @return   PageManager
	 */
	private function getPageManager()
	{
		return $this->getContext()->getService('StaticWeb\\PageManager');
	}

}
