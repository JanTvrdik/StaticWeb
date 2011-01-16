<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Reflection
 */



/**
 * Reports information about a function.
 *
 * @author     David Grudl
 */
class FunctionReflection extends ReflectionFunction
{
	/** @var string|Closure */
	private $value;


	public function __construct($name)
	{
		parent::__construct($this->value = $name);
	}



	public function __toString()
	{
		return 'Function ' . $this->getName() . '()';
	}



	public function getClosure()
	{
		return $this->isClosure() ? $this->value : NULL;
	}



	/********************* Reflection layer ****************d*g**/



	/**
	 * @return ExtensionReflection
	 */
	public function getExtension()
	{
		return ($name = $this->getExtensionName()) ? new ExtensionReflection($name) : NULL;
	}



	public function getParameters()
	{
		foreach ($res = parent::getParameters() as $key => $val) {
			$res[$key] = new ParameterReflection($this->value, $val->getName());
		}
		return $res;
	}



	/********************* Object behaviour ****************d*g**/



	/**
	 * @return ClassReflection
	 */
	public function getReflection()
	{
		return new ClassReflection($this);
	}



	public function __call($name, $args)
	{
		return ObjectMixin::call($this, $name, $args);
	}



	public function &__get($name)
	{
		return ObjectMixin::get($this, $name);
	}



	public function __set($name, $value)
	{
		return ObjectMixin::set($this, $name, $value);
	}



	public function __isset($name)
	{
		return ObjectMixin::has($this, $name);
	}



	public function __unset($name)
	{
		throw new MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");
	}

}
