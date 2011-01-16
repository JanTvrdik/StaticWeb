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
 * Supplemental MS SQL database driver.
 *
 * @author     David Grudl
 */
class PdoMsSqlDriver extends Object implements ISupplementalDriver
{
	/** @var Connection */
	private $connection;



	public function __construct(Connection $connection, array $options)
	{
		$this->connection = $connection;
	}



	/********************* SQL ****************d*g**/



	/**
	 * Delimites identifier for use in a SQL statement.
	 */
	public function delimite($name)
	{
		// @see http://msdn.microsoft.com/en-us/library/ms176027.aspx
		return '[' . str_replace(array('[', ']'), array('[[', ']]'), $name) . ']';
	}



	/**
	 * Formats date-time for use in a SQL statement.
	 */
	public function formatDateTime(DateTime $value)
	{
		return $value->format("'Y-m-d H:i:s'");
	}



	/**
	 * Encodes string for use in a LIKE statement.
	 */
	public function formatLike($value, $pos)
	{
		$value = strtr($value, array("'" => "''", '%' => '[%]', '_' => '[_]', '[' => '[[]'));
		return ($pos <= 0 ? "'%" : "'") . $value . ($pos >= 0 ? "%'" : "'");
	}



	/**
	 * Injects LIMIT/OFFSET to the SQL query.
	 */
	public function applyLimit(&$sql, $limit, $offset)
	{
		// offset support is missing
		if ($limit >= 0) {
			$sql = 'SELECT TOP ' . (int) $limit . ' * FROM (' . $sql . ') t';
		}

		if ($offset) {
			throw new NotImplementedException('Offset is not implemented.');
		}
	}



	/**
	 * Normalizes result row.
	 */
	public function normalizeRow($row, $statement)
	{
		return $row;
	}

}
