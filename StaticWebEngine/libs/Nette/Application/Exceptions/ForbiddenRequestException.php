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
 * Forbidden request exception - access denied.
 *
 * @author     David Grudl
 */
class ForbiddenRequestException extends BadRequestException
{
	/** @var int */
	protected $defaultCode = 403;

}
