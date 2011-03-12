<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette\Application;

use Nette;



/**
 * Web form as presenter component.
 *
 * @author     David Grudl
 *
 * @property-read Presenter $presenter
 */
class AppForm extends Nette\Forms\Form implements ISignalReceiver
{

	/**
	 * Application form constructor.
	 */
	public function __construct(Nette\IComponentContainer $parent = NULL, $name = NULL)
	{
		parent::__construct();
		$this->monitor('Nette\Application\Presenter');
		if ($parent !== NULL) {
			$parent->addComponent($this, $name);
		}
	}



	/**
	 * Returns the presenter where this component belongs to.
	 * @param  bool   throw exception if presenter doesn't exist?
	 * @return Presenter|NULL
	 */
	public function getPresenter($need = TRUE)
	{
		return $this->lookup('Nette\Application\Presenter', $need);
	}



	/**
	 * This method will be called when the component (or component's parent)
	 * becomes attached to a monitored object. Do not call this method yourself.
	 * @param  IComponent
	 * @return void
	 */
	protected function attached($presenter)
	{
		if ($presenter instanceof Presenter) {
			$name = $this->lookupPath('Nette\Application\Presenter');

			if (!isset($this->getElementPrototype()->id)) {
				$this->getElementPrototype()->id = 'frm-' . $name;
			}

			$this->setAction(new Link(
				$presenter,
				$name . self::NAME_SEPARATOR . 'submit!',
				array()
			));

			// fill-in the form with HTTP data
			if ($this->isSubmitted()) {
				foreach ($this->getControls() as $control) {
					$control->loadHttpData();
				}
			}
		}
		parent::attached($presenter);
	}



	/**
	 * Tells if the form is anchored.
	 * @return bool
	 */
	public function isAnchored()
	{
		return (bool) $this->getPresenter(FALSE);
	}



	/**
	 * Internal: receives submitted HTTP data.
	 * @return array
	 */
	protected function receiveHttpData()
	{
		$presenter = $this->getPresenter();
		if (!$presenter->isSignalReceiver($this, 'submit')) {
			return;
		}

		$isPost = $this->getMethod() === self::POST;
		$request = $presenter->getRequest();
		if ($request->isMethod('forward') || $request->isMethod('post') !== $isPost) {
			return;
		}

		if ($isPost) {
			return Nette\ArrayTools::mergeTree($request->getPost(), $request->getFiles());
		} else {
			return $request->getParams();
		}
	}



	/********************* interface ISignalReceiver ****************d*g**/



	/**
	 * This method is called by presenter.
	 * @param  string
	 * @return void
	 */
	public function signalReceived($signal)
	{
		if ($signal === 'submit') {
			$this->fireEvents();

		} else {
			throw new BadSignalException("Missing handler for signal '$signal' in {$this->reflection->name}.");
		}
	}

}
