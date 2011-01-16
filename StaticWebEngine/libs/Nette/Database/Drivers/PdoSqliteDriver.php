<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2011 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Drivers
 */



/**
 * Supplemental SQLite3 database driver.
 *
 * @author     David Grudl
 */
class PdoSqliteDriver extends Object implements ISupplementalDriver
{
	/** @var Connection */
	private $connection;

	/** @var string  Datetime format */
	private $fmtDateTime;



	public function __construct(Connection $connection, array $options)
	{
		$this->connection = $connection;
		$this->fmtDateTime = isset($options['formatDateTime']) ? $options['formatDateTime'] : 'U';
	}



	/********************* SQL ****************d*g**/



	/**
	 * Delimites identifier for use in a SQL statement.
	 */
	public function delimite($name)
	{
		return '[' . strtr($name, '[]', '  ') . ']';
	}



	/**
	 * Formats date-time for use in a SQL statement.
	 */
	public function formatDateTime(DateTime $value)
	{
		return $value->format($this->fmtDateTime);
	}



	/**
	 * Encodes string for use in a LIKE statement.
	 */
	public function formatLike($value, $pos)
	{
		$value = addcslashes(substr($this->connection->quote($value), 1, -1), '%_\\');
		return ($pos <= 0 ? "'%" : "'") . $value . ($pos >= 0 ? "%'" : "'") . " ESCAPE '\\'";
	}



	/**
	 * Injects LIMIT/OFFSET to the SQL query.
	 */
	public function applyLimit(&$sql, $limit, $offset)
	{
		if ($limit < 0 && $offset < 1) return;
		$sql .= ' LIMIT ' . $limit . ($offset > 0 ? ' OFFSET ' . (int) $offset : '');
	}



	/**
	 * Normalizes result row.
	 */
	public function normalizeRow($row, $statement)
	{
		return $row;
	}

}
