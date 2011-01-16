<?php
/**
 * Static Web.
 *
 * @copyright    Copyright (c) 2010 Jan Tvrdík
 * @license      MIT
 * @package      StaticWeb
 */

require_once dirname(__FILE__) . '/BaseStaticPresenter.php';



/**
 * Error presenter
 *
 * @author   Jan Tvrdík
 */
final class ErrorPresenter extends BaseStaticPresenter
{
	/**
	 * Processes request.
	 *
	 * @author   Jan Tvrdík
	 * @param    PresenterRequest
	 * @return   void
	 * @throws   AbortException|InvalidStateException
	 */
	public function processRequest(PresenterRequest $request)
	{
		$params = $request->getParams();
		$exception = & $params['exception'];
		if (!isset($exception)) {
			throw new InvalidStateException('Missing required parameter - exception.');
		}

		if ($exception instanceof BadRequestException) {
			$code = $exception->getCode();
			$name = in_array($code, array(403, 404, 405, 410, 500)) ? $code : '4xx';

		} else {
			Debug::log($exception, Debug::ERROR);
			$name = '500';
		}

		$this->page = '@errors/' . $name;
		$this->sendTemplate();
	}

}
