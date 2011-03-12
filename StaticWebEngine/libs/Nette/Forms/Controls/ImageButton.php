<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 */

namespace Nette\Forms;

use Nette;



/**
 * Submittable image button form control.
 *
 * @author     David Grudl
 */
class ImageButton extends SubmitButton
{

	/**
	 * @param  string  URI of the image
	 * @param  string  alternate text for the image
	 */
	public function __construct($src = NULL, $alt = NULL)
	{
		parent::__construct();
		$this->control->type = 'image';
		$this->control->src = $src;
		$this->control->alt = $alt;
	}



	/**
	 * Returns HTML name of control.
	 * @return string
	 */
	public function getHtmlName()
	{
		$name = parent::getHtmlName();
		return strpos($name, '[') === FALSE ? $name : $name . '[]';
	}



	/**
	 * Loads HTTP data.
	 * @return void
	 */
	public function loadHttpData()
	{
		$path = $this->getHtmlName(); // img_x or img['x']
		$path = explode('[', strtr(str_replace(']', '', strpos($path, '[') === FALSE ? $path . '.x' : substr($path, 0, -2)), '.', '_'));
		$this->setValue(Nette\ArrayTools::get($this->getForm()->getHttpData(), $path) !== NULL);
	}

}
