<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

// no namespace



/*
some useful SPL exception:

- LogicException
	- InvalidArgumentException
	- LengthException
- RuntimeException
	- OutOfBoundsException
	- UnexpectedValueException

other SPL exceptions are ambiguous; do not use them

ErrorException is corrupted in PHP < 5.3
*/



/**
 * The exception that is thrown when the value of an argument is outside the allowable range of values as defined by the invoked method.
 */
class ArgumentOutOfRangeException extends InvalidArgumentException
{
}



/**
 * The exception that is thrown when a method call is invalid for the object's current state, method has been invoked at an illegal or inappropriate time.
 */
class InvalidStateException extends RuntimeException
{
	}



/**
 * The exception that is thrown when a requested method or operation is not implemented.
 */
class NotImplementedException extends LogicException
{
}



/**
 * The exception that is thrown when an invoked method is not supported.
 * For scenarios where it is sometimes possible to perform the requested operation, see InvalidStateException.
 */
class NotSupportedException extends LogicException
{
}



/**
 * The exception that is thrown when a requested method or operation is deprecated.
 */
class DeprecatedException extends NotSupportedException
{
}



/**
 * The exception that is thrown when accessing a class member (property or method) fails.
 */
class MemberAccessException extends LogicException
{
}



/**
 * The exception that is thrown when an I/O error occurs.
 */
class IOException extends RuntimeException
{
}



/**
 * The exception that is thrown when accessing a file that does not exist on disk.
 */
class FileNotFoundException extends IOException
{
}



/**
 * The exception that is thrown when part of a file or directory cannot be found.
 */
class DirectoryNotFoundException extends IOException
{
}



/**
 * The exception that indicates errors that can not be recovered from.
 * Execution of the script should be halted.
 */

class FatalErrorException extends ErrorException
{

	public function __construct($message, $code, $severity, $file, $line, $context)
	{
		parent::__construct($message, $code, $severity, $file, $line);
		$this->context = $context;
	}

}


