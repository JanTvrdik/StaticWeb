<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Mail
 */



/**
 * Mailer interface.
 *
 * @author     David Grudl
 */
interface IMailer
{

	/**
	 * Sends email.
	 * @param  Mail
	 * @return void
	 */
	function send(Mail $mail);

}
