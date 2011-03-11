<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette\Application;

use Nette;



/**
 * Bad HTTP / presenter request exception.
 *
 * @author     David Grudl
 */
class BadRequestException extends \Exception
{
	/** @var int */
	protected $defaultCode = 404;


	public function __construct($message = '', $code = 0, \Exception $previous = NULL)
	{
		if ($code < 200 || $code > 504)	{
			$code = $this->defaultCode;
		}

		{
			parent::__construct($message, $code, $previous);
		}
	}

}
