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
 * Hidden form control used to store a non-displayed value.
 *
 * @author     David Grudl
 */
class HiddenField extends FormControl
{
	/** @var string */
	private $forcedValue;



	public function __construct($forcedValue = NULL)
	{
		parent::__construct();
		$this->control->type = 'hidden';
		$this->value = (string) $forcedValue;
		$this->forcedValue = $forcedValue;
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
	 * Sets control's value.
	 * @param  string
	 * @return HiddenField  provides a fluent interface
	 */
	public function setValue($value)
	{
		$this->value = is_scalar($value) ? (string) $value : '';
		return $this;
	}



	/**
	 * Generates control's HTML element.
	 * @return Html
	 */
	public function getControl()
	{
		return parent::getControl()->value($this->forcedValue === NULL ? $this->value : $this->forcedValue)->data('nette-rules', NULL);
	}

}
