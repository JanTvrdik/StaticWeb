<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Application
 */



/**
 * Rendering presenter response.
 *
 * @author     David Grudl
 */
class RenderResponse extends Object implements IPresenterResponse
{
	/** @var mixed */
	private $source;



	/**
	 * @param  mixed  renderable variable
	 */
	public function __construct($source)
	{
		$this->source = $source;
	}



	/**
	 * @return mixed
	 */
	final public function getSource()
	{
		return $this->source;
	}



	/**
	 * Sends response to output.
	 * @return void
	 */
	public function send()
	{
		if ($this->source instanceof ITemplate) {
			$this->source->render();

		} else {
			echo $this->source;
		}
	}

}
