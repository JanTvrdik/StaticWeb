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
		$path = $httpRequest->getUri()->getRelativeUri();

		if ($path === '') {
			$path = $this->homepage;
		} elseif (substr($path, -1) === '/') {
			$path .= $this->defaultPage;
		}

		return new PresenterRequest(
			$this->presenter,
			$httpRequest->getMethod(),
			array('page' => $path),
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

}
