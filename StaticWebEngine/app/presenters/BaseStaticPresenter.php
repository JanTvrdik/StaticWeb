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
use Nette\Application\IPresenterResponse;
use Nette\Application\RenderResponse;
use Nette\Application\BadRequestException;
use Nette\Application\AbortException;
use Nette\Application\RedirectingResponse;
use Nette\Web\IHttpResponse;



/**
 * Base presenter for all static pages.
 *
 * @author   Jan Tvrdík
 */
abstract class BaseStaticPresenter extends Nette\Object implements Nette\Application\IPresenter
{
	/** @var     string            page (without leading and trailing slash, for example: "about" or "books/linux") */
	protected $page;

	/** @var     PresenterRequest */
	private $request;

	/** @var     IPresenterResponse */
	private $response;



	/**
	 * Returns presenter request.
	 *
	 * @author   Jan Tvrdík
	 * @return   PresenterRequest
	 */
	final public function getRequest()
	{
		return $this->request;
	}



	/**
	 * Processes given request and returns a response.
	 *
	 * @author   Jan Tvrdík, David Grudl
	 * @param    PresenterRequest
	 * @return   IPresenterResponse
	 * @throws   BadRequestException
	 */
	public function run(PresenterRequest $request)
	{
		try {
			$this->request = $request;
			$this->canonicalize();
			$this->processRequest($request);
			throw new BadRequestException();

		} catch (AbortException $e) {
			return $this->response;
		}
	}



	/**
	 * Processes given request.
	 *
	 * @author   Jan Tvrdík
	 * @param    PresenterRequest
	 * @return   void
	 * @throws   AbortException
	 */
	abstract public function processRequest(PresenterRequest $request);



	/**
	 * Conditional redirect to canonicalized URI.
	 *
	 * @author   David Grudl, Jan Tvrdík
	 * @return   void
	 * @throws   AbortException
	 */
	public function canonicalize()
	{
		if ($this->request->isMethod('get') || $this->request->isMethod('head')) {
			$uri = $this->getApplication()->getRouter()->constructUrl(clone $this->request, $this->getHttpRequest()->getUri());
			if ($uri !== NULL && !$this->getHttpRequest()->getUri()->isEqual($uri)) {
				$this->sendResponse(new RedirectingResponse($uri, IHttpResponse::S301_MOVED_PERMANENTLY));
			}
		}
	}



	/**
	 * Correctly terminates presenter.
	 *
	 * @author   David Grudl
	 * @return   void
	 * @throws   AbortException
	 */
	public function terminate()
	{
		throw new AbortException();
	}



	/**
	 * Sends response and terminates presenter.
	 *
	 * @author   David Grudl
	 * @param    IPresenterResponse
	 * @return   void
	 * @throws   AbortException
	 */
	public function sendResponse(IPresenterResponse $response)
	{
		$this->response = $response;
		$this->terminate();
	}



	/**
	 * Sends RenderResponse.
	 *
	 * @author   Jan Tvrdík
	 * @return   void
	 * @throws   AbortException|BadRequestException
	 */
	public function sendTemplate()
	{
		try {
			$file = $this->getTemplatePath();
			$template = $this->createTemplate($file);

		} catch (FileNotFoundException $e) {
			throw new BadRequestException("Page not found. Missing template '$file'.");
		}

		if ($layout = $this->getLayoutPath()) { // intentionally =
			$template->layout = $layout;
			$template->_extends = $layout;
		}

		$this->sendResponse(new RenderResponse($template));
	}



	/**
	 * Creates and returns configured template.
	 *
	 * @author   Jan Tvrdík, David Grudl
	 * @param    string            template file path
	 * @return   FileTemplate
	 */
	protected function createTemplate($path = NULL)
	{
		$template = new Nette\Templates\FileTemplate($path);

		// default parameters
		$template->baseUri = rtrim(Env::getVariable('baseUri', NULL), '/');
		$template->basePath = preg_replace('#https?://[^/]+#A', '', $template->baseUri);

		// default filters
		$template->onPrepareFilters[] = function($template) {
			$template->registerFilter(new Nette\Templates\LatteFilter());
		};

		// default helpers
		$template->registerHelper('escape', 'Nette\Templates\TemplateHelpers::escapeHtml');
		$template->registerHelper('escapeUrl', 'rawurlencode');
		$template->registerHelper('stripTags', 'strip_tags');
		$template->registerHelper('nl2br', 'nl2br');
		$template->registerHelper('substr', 'iconv_substr');
		$template->registerHelper('repeat', 'str_repeat');
		$template->registerHelper('replaceRE', 'Nette\String::replace');
		$template->registerHelper('implode', 'implode');
		$template->registerHelper('number', 'number_format');
		$template->registerHelperLoader('Nette\Templates\TemplateHelpers::loader');

		return $template;
	}



	/**
	 * Returns absolute filesystem path to template.
	 *
	 * @author   Jan Tvrdík
	 * @return   string
	 * @throws   InvalidStateException
	 */
	protected function getTemplatePath()
	{
		if ($this->page === NULL) throw new InvalidStateException();
		return TEMPLATES_DIR . '/' . $this->page . '.latte';
	}



	/**
	 * Returns absolute filesystem path to layout or FALSE if layout does not exist.
	 *
	 * @author   Jan Tvrdík
	 * @return   string|FALSE
	 * @throws   InvalidStateException
	 */
	protected function getLayoutPath()
	{
		if ($this->page === NULL) throw new InvalidStateException();
		$dir = $this->page;
		do {
			$dir = substr($dir, 0, strrpos($dir, '/'));
			$path = TEMPLATES_DIR . '/' . ($dir ? $dir . '/' : '') . '@layout.latte';
			if (is_file($path)) return $path;
			elseif (empty($dir)) return FALSE;
		} while (TRUE);
	}



	/**
	 * Returns current HTTP request.
	 *
	 * @author   David Grudl
	 * @return   Nette\Web\HttpRequest
	 */
	protected function getHttpRequest()
	{
		return Env::getHttpRequest();
	}



	/**
	 * Returns Application instance.
	 *
	 * @author   David Grudl
	 * @return   Nette\Application\Application
	 */
	protected function getApplication()
	{
		return Env::getApplication();
	}

}
