<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette
 */



/**
 * Tools library.
 *
 * @author     David Grudl
 */
final class Tools
{
	/** minute in seconds */
	const MINUTE = 60;

	/** hour in seconds */
	const HOUR = 3600;

	/** day in seconds */
	const DAY = 86400;

	/** week in seconds */
	const WEEK = 604800;

	/** average month in seconds */
	const MONTH = 2629800;

	/** average year in seconds */
	const YEAR = 31557600;

	/** @var resource {@link Tools::enterCriticalSection()} */
	private static $criticalSections;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * DateTime object factory.
	 * @param  string|int|DateTime
	 * @return DateTime
	 */
	public static function createDateTime($time)
	{
		if ($time instanceof DateTime) {
			return clone $time;

		} elseif (is_numeric($time)) {
			if ($time <= self::YEAR) {
				$time += time();
			}
			return new DateTime53(date('Y-m-d H:i:s', $time));

		} else { // textual or NULL
			return new DateTime53($time);
		}
	}



	/**
	 * Gets the boolean value of a configuration option.
	 * @param  string  configuration option name
	 * @return bool
	 */
	public static function iniFlag($var)
	{
		$status = strtolower(ini_get($var));
		return $status === 'on' || $status === 'true' || $status === 'yes' || (int) $status;
	}



	/**
	 * Initializes variable with $default value.
	 * @param  mixed  variable
	 * @param  mixed  default value
	 * @return void
	 */
	public static function defaultize(&$var, $default)
	{
		if ($var === NULL) $var = $default;
	}



	/**
	 * Compares two values.
	 * @param  mixed
	 * @param  mixed
	 * @return bool
	 */
	public static function compare($l, $operator, $r)
	{
		switch ($operator) {
		case '>':
			return $l > $r;
		case '>=':
			return $l >= $r;
		case '<':
			return $l < $r;
		case '<=':
			return $l <= $r;
		case '=':
		case '==':
			return $l == $r;
		case '!':
		case '!=':
		case '<>':
			return $l != $r;
		}
		throw new InvalidArgumentException("Unknown operator $operator.");
	}



	/**
	 * Returns the MIME content type of file.
	 * @param  string
	 * @return string
	 */
	public static function detectMimeType($file)
	{
		if (!is_file($file)) {
			throw new FileNotFoundException("File '$file' not found.");
		}

		$info = @getimagesize($file); // @ - files smaller than 12 bytes causes read error
		if (isset($info['mime'])) {
			return $info['mime'];

		} elseif (extension_loaded('fileinfo')) {
			$type = preg_replace('#[\s;].*$#', '', finfo_file(finfo_open(FILEINFO_MIME), $file));

		} elseif (function_exists('mime_content_type')) {
			$type = mime_content_type($file);
		}

		return isset($type) && preg_match('#^\S+/\S+$#', $type) ? $type : 'application/octet-stream';
	}



	/**
	 * Returns the MIME content type of file.
	 * @param  string
	 * @return string
	 */
	public static function detectMimeTypeFromString($data)
	{
		if (extension_loaded('fileinfo') && preg_match('#^(\S+/[^\s;]+)#', finfo_buffer(finfo_open(FILEINFO_MIME), $data), $m)) {
			return $m[1];

		} elseif (strncmp($data, "\xff\xd8", 2) === 0) {
			return 'image/jpeg';

		} elseif (strncmp($data, "\x89PNG", 4) === 0) {
			return 'image/png';

		} elseif (strncmp($data, "GIF", 3) === 0) {
			return 'image/gif';

		} else {
			return 'application/octet-stream';
		}
	}



	/********************* critical section ****************d*g**/



	/**
	 * Enters the critical section, other threads are locked out.
	 * @return void
	 */
	public static function enterCriticalSection()
	{
		if (self::$criticalSections) {
			throw new InvalidStateException('Critical section has already been entered.');
		}
		$handle = ($tmp= @fopen(NETTE_DIR . '/lockfile', 'r')) ? $tmp : fopen(NETTE_DIR . '/lockfile', 'w'); // @ - file may not already exist
		if (!$handle) {
			throw new InvalidStateException("Unable initialize critical section (missing file '" . NETTE_DIR . "/lockfile').");
		}
		flock(self::$criticalSections = $handle, LOCK_EX);
	}



	/**
	 * Leaves the critical section, other threads can now enter it.
	 * @return void
	 */
	public static function leaveCriticalSection()
	{
		if (!self::$criticalSections) {
			throw new InvalidStateException('Critical section has not been initialized.');
		}
		flock(self::$criticalSections, LOCK_UN);
		fclose(self::$criticalSections);
		self::$criticalSections = NULL;
	}

}