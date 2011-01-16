<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Forms
 */



/**
 * Push button control with no default behavior.
 *
 * @author     David Grudl
 */
class Button extends FormControl
{

	/**
	 * @param  string  caption
	 */
	public function __construct($caption = NULL)
	{
		parent::__construct($caption);
		$this->control->type = 'button';
	}



	/**
	 * Bypasses label generation.
	 * @return void
	 */
	public function getLabel($caption = NULL)
	{
		return NULL;
	}



	/**
	 * Generates control's HTML element.
	 * @param  string
	 * @return Html
	 */
	public function getControl($caption = NULL)
	{
		$control = parent::getControl();
		$control->value = $this->translate($caption === NULL ? $this->caption : $caption);
		return $control;
	}

}
