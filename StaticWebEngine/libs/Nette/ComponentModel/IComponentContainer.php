<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace Nette;

use Nette;



/**
 * Containers are objects that logically contain zero or more IComponent components.
 *
 * @author     David Grudl
 */
interface IComponentContainer extends IComponent
{

	/**
	 * Adds the specified component to the IComponentContainer.
	 * @param  IComponent
	 * @param  string
	 * @return void
	 */
	function addComponent(IComponent $component, $name);

	/**
	 * Removes a component from the IComponentContainer.
	 * @param  IComponent
	 * @return void
	 */
	function removeComponent(IComponent $component);

	/**
	 * Returns single component.
	 * @param  string
	 * @return IComponent|NULL
	 */
	function getComponent($name);

	/**
	 * Iterates over a components.
	 * @param  bool    recursive?
	 * @param  string  class types filter
	 * @return \Iterator
	 */
	function getComponents($deep = FALSE, $filterType = NULL);

}
