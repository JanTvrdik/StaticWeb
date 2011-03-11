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

	/** @var     Nette\IContext */
	private $context;



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
	 * Returns current page.
	 *
	 * @author   Jan Tvrdík
	 * @return   string
	 */
	final public function getPage()
	{
		return $this->page;
	}



	/**
	 * Generates link to a page.
	 *
	 * @todo     Do not use "StaticPage"!
	 * @todo     Write syntax examples in phpDoc
	 *
	 * @author   Jan Tvrdík
	 * @param    string
	 * @return   string
	 */
	public function link($page)
	{
		if ($page[0] === '/') {
			$page = substr($page, 1);
		} else {
			$pos = strrpos($this->page, '/');
			$page = ($pos ? (substr($this->page, 0, $pos) . '/') : '') . $page;
		}
		$parts = explode('/', $page);
		$page = '';
		foreach ($parts as $part) {
			if ($part === '.' ) {
				continue;
			} elseif ($part === '') {
				throw new Nette\Application\InvalidLinkException("Invalid link: '$s'");
			} elseif ($part === '..') {
				$pos = strrpos($page, '/');
				if ($pos === FALSE) throw new Nette\Application\InvalidLinkException("Invalid link: '$s'");
				$page = substr($page, 0, $pos);
			} else {
				$page .= ($page ? '/' : '') . $part;
			}
		}

		return $this->getApplication()->getRouter()->constructUrl(
			new PresenterRequest('StaticPage', 'get', array('page' => $page)),
			$this->getHttpRequest()->getUri()
		);
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
			$currentUri = $this->getHttpRequest()->getUri();
			$optimalUri = $this->getApplication()->getRouter()->constructUrl(clone $this->request, $currentUri);
			if ($optimalUri !== NULL && !$currentUri->isEqual($optimalUri)) {
				$this->sendResponse(new RedirectingResponse($optimalUri, IHttpResponse::S301_MOVED_PERMANENTLY));
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
	 * @throws   AbortException|BadRequestException|InvalidStateException
	 */
	public function sendTemplate()
	{
		if ($this->page === NULL) throw new \InvalidStateException("Can not send template, because {$this->reflection->name}::\$page is not set.");

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
		$template->presenter = $this;

		// default filters
		$template->onPrepareFilters[] = function($template) {
			require_once APP_DIR . '/classes/ConfiguredTexy.php';
			require_once APP_DIR . '/classes/TexyElementsFilter.php';
			require_once APP_DIR . '/classes/LatteMacros.php';

			$texyElementsFilter = new TexyElementsFilter();
			$texyElementsFilter->texy = new ConfiguredTexy();
			$texyElementsFilter->autoChangeSyntax = TRUE;

			$latteHandler = new LatteMacros();
			$latteHandler->macros['@href'] = ' href="<?php echo %:escape%(%:macroPageLink%) ?>"';
			$latteFilter = new Nette\Templates\LatteFilter();
			$latteFilter->setHandler($latteHandler);

			$template->registerFilter($texyElementsFilter);
			$template->registerFilter($latteFilter);
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
	 */
	protected function getTemplatePath()
	{
		return $this->getTemplateLocator()->getTemplatePath($this->page);
	}



	/**
	 * Returns absolute filesystem path to layout or FALSE if layout does not exist.
	 *
	 * @author   Jan Tvrdík
	 * @return   string|FALSE
	 */
	protected function getLayoutPath()
	{
		return $this->getTemplateLocator()->getLayoutPath($this->page);
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
	 * Returns current HTTP request.
	 *
	 * @author   David Grudl
	 * @return   Nette\Web\HttpRequest
	 */
	protected function getHttpRequest()
	{
		return $this->context->getService('Nette\\Web\\IHttpRequest');
	}



	/**
	 * Returns Application instance.
	 *
	 * @author   David Grudl
	 * @return   Nette\Application\Application
	 */
	protected function getApplication()
	{
		return $this->context->getService('Nette\\Application\\Application');
	}



	/**
	 * Returns TemplateLocator.
	 *
	 * @author   Jan Tvrdík
	 * @return   TemplateLocator
	 */
	protected function getTemplateLocator()
	{
		return $this->context->getService('StaticWeb\\TemplateLocator');
	}

}
