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
 * Single validation rule or condition represented as value object.
 *
 * @author     David Grudl
 */
final class Rule extends Object
{
	/** type */
	const CONDITION = 1;

	/** type */
	const VALIDATOR = 2;

	/** type */
	const FILTER = 3;

	/** @var IFormControl */
	public $control;

	/** @var mixed */
	public $operation;

	/** @var mixed */
	public $arg;

	/** @var int (CONDITION, VALIDATOR, FILTER) */
	public $type;

	/** @var bool */
	public $isNegative = FALSE;

	/** @var string (only for VALIDATOR type) */
	public $message;

	/** @var Rules (only for CONDITION type)  */
	public $subRules;

}
