<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette\Database;

use Nette;



/**
 * SQL literal value.
 *
 * @author     Jakub Vrana
 */
class SqlLiteral
{
	/** @var string */
	public $value = '';


	function __construct($value)
	{
		$this->value = (string) $value;
	}

}
