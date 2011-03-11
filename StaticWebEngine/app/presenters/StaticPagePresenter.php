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
use Nette\Application\BadRequestException;

require_once dirname(__FILE__) . '/BaseStaticPresenter.php';



/**
 * Presenter for static pages
 *
 * @author   Jan Tvrdík
 */
final class StaticPagePresenter extends BaseStaticPresenter
{
	/**
	 * Processes given request.
	 *
	 * @author   Jan Tvrdík
	 * @param    PresenterRequest
	 * @return   void
	 * @throws   Nette\Applicationy\AbortException|BadRequestException
	 */
	public function processRequest(PresenterRequest $request)
	{
		$params = $request->getParams();
		if (!isset($params['page'])) {
			throw new BadRequestException('Invalid request. Parameter \'page\' is required.');
		}
		$this->page = $params['page'];
		if (!Nette\String::match($this->page, '#^[a-z0-9]([a-z0-9_.-]*[a-z0-9])?(/[a-z0-9]([a-z0-9_.-]*[a-z0-9])?)*$#i')) {
			throw new BadRequestException('Parameter \'page\' contains illegal characters.');
		}
		$this->sendTemplate();
	}
}
