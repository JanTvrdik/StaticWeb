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

	/** @var     Nette\IContext */
	private $context;



	/**
	 * Class constructor.
	 *
	 * @author   Jan Tvrdík
	 * @param    string            presenter name
	 * @param    string            page used as homepage
	 * @param    string            default page for (sub)categories
	 */
	public function __construct($presenter, $homepage, $defaultPage)
	{
		$this->presenter = $presenter;
		$this->homepage = $homepage;
		$this->defaultPage = $defaultPage;
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
		$path = rtrim($httpRequest->getUri()->getRelativeUri(), '/');
		$page = ($path === '' ? $this->homepage : $path);
		$templateLocator = $this->getTemplateLocator();
		if (!$templateLocator->existsPage($page)) {
			$page .= '/' . $this->defaultPage;
			if (!$templateLocator->existsPage($page)) {
				return NULL;
			}
		}

		return new PresenterRequest(
			$this->presenter,
			$httpRequest->getMethod(),
			array('page' => $page),
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

		return $refUri->getBaseUri() . $page;
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
	 * Returns template locator.
	 *
	 * @author   Jan Tvrdík
	 * @return   TemplateLocator
	 */
	protected function getTemplateLocator()
	{
		return $this->getContext()->getService('StaticWeb\\TemplateLocator');
	}

}
