<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */
  
# Hybrid Namespace
namespace Hybrid\Application\Library\Database;

use Exception;
use PDO;
use PDOException;

use Hybrid\Application\Library\Configuration;

# Hybrid Security Authication Check
if(!defined('HybridSecure'))
{
	if(class_exists('\Hybrid\Application\Library\Configuration') == true)
	{
		try {
			$application = \Hybrid\Application\Library\Configuration::get('app');
			
			if(isset($application, $application['url']) && strpos('404', $_SERVER['REQUEST_URI']) == false)
			{
					$location = sprintf('Location: %s/index.php/404', $application['url']);
					header($location);
					unset($application);
			}
		} catch( Exception $ex ) {}
	}
	echo 'Sorry an inteneral application error has occurred.';
	$error = sprintf('[AUTH] The file %s was denied access by %s', basename(__FILE__), $_SERVER['REMOTE_ADDR']);
	error_log($error);
	exit;
}

/**
 * Database Adapter
 */
class Adapter
{
	protected $log_errors  = true;
	protected $show_errors = true;
	
	protected $connection;
	protected $result;
	
	public function __construct()
	{
		throw new Exception('Could not finish constructing Adapter');
		try {
			$this->connect();
		} catch ( PDOException $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		} catch ( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function connect()
	{
		echo 'connection test' . PHP_EOL;
		$connection = Configuration::get('connect');
		
		if(!$this->connection instanceof PDO)
		{
			try {
				$this->connection = new PDO($this->formatDNS(), $connection['username'], $connection['password']);
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch( PDOException $ex ) {
				if(true == $this->log_errors)
				{
					error_log('[DATABASE] ' . $ex->getMessage());
				}
				if(true == $this->show_errors)
				{
					throw new Exception($ex->getMessage(), $ex->getCode());
				}
			}
		}
	}
	
	public function formatDNS($type = 'mysql')
	{
		$type = strtoupper($type);
		$con  = Configuration::get('connect');
		$dns  = Configuration::get('dns');
		
		if(false == array_key_exists($type, $dns))
		{
			throw Exception('Database Driver ', $type, ' was not found.');
		}
		
		$port  = isset($con['port']) ? $con['port'] : $dns[$type]['port'];
		$rules = array(
			'{hostname}' => $con['hostname'],
			'{username}' => $con['username'],
			'{password}' => $con['password'],
			'{database}' => $con['database'],
			'{port}'	 => $port
		);
		
		$data = str_replace(array_keys($rules), array_values($values), $dns[$type]['dns']);
		
		return $data;
	}
	
	public function query($query)
	{
		try {
			
			if(false == is_string($query) || true == empty($query))
			{
				throw new Exception('Database query must be a string');
			}
		
			$this->connect();
		
			if(false == ($this->result = $this->connection->query($query)))
			{
				if(true == $this->log_errors)
				{
					error_log('[DATABASE] Database Query Failed to Execute.');
				}
				if(true == $this->show_errors)
				{
					throw new Exception('Database Query Failed to Execute');
				}
			}
			
		} catch( PDOException $ex ) {
			error_log('[DATABASE] ' . $ex->getMessage());
			if(true == $this->show_errors)
			{
				throw new Exception($ex->getMessage(), $ex->getCode());
			}
		}
	}
	
	public function select($table, $where = '', $fields = '*', $limit = NULL, $offset = NULL)
	{
		try {
			
			$query = sprintf('SELECT %s WHERE %s', $fields, $table);
			
			if($where != '' || isset($where))
			{
				if(true == is_array($where))
				{
					$data = array();
					foreach($where as $key => $value)
					{
						$data[] = sprintf('%s=%s', $key, $this->qouteValue($value));
					}
					$query .= sprintf(' WHERE %s', implode(', ', $where));
				}
			}
			
			if(false == is_null($limit) && true == is_int($limit))
			{
				$query .= sprintf(' LIMIT %d', $limit);
			}
			
			if(false == is_null($offset) && true == is_int($offset))
			{
				$query .= sprintf(' OFFSET %d', $offset);
			}
			
			$this->query( $query );
			
			return $this->countRows();
			
		} catch( PDOException $ex ) {
			error_log('[DATABASE] ' . $ex->getMessage());
			if(true == $this->show_errors)
			{
				throw new Exception($ex->getMessage(), $ex->getCode());
			}
		}
	}
	
	public function insert($table, array $data)
	{
		try {
			
			$fields	= implode(',', array_keys($data));
			$values = implode(',', array_map(array($this, 'qouteValues'), array_values($data)));
			
			$query  = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $fields, $values);
			
			$this->query($query);
			return $this->getInsertID();
			
			
		} catch( PDOException $ex ) {
			error_log('[DATABASE] ' . $ex->getMessage());
			if(true == $this->show_errors)
			{
				throw new Exception($ex->getMessage(), $ex->getCode());
			}
		}
	}
	
	public function update($table, array $data, $where = '')
	{
		try {
			
			$set = array();
			
			foreach($data as $field => $value)
			{
				$set[] = sprintf('%s=%s', $field, $this->qouteValue($value));
			}
			
			$set = implode(', ', $set);
			
			$query = sprintf('UPDATE %s SET %s', $table, $set);
			
			if(false == empty($where) || true == isset($where))
			{
				if(true == is_array($where))
				{
					$data = array();
					foreach($where as $key => $value)
					{
						$data[] = sprintf('%s=%s', $key, $this->qouteValue($value));
					}
					$where = implode(', ', $data);
				}
				$query .= sprintf(' WHERE %s', $where);
			}
			
			$this->query( $query );
			return $this->getAffactedRows();
			
		} catch( PDOException $ex ) {
			error_log('[DATABASE] ' . $ex->getMessage());
			if(true == $this->show_errors)
			{
				throw new Exception($ex->getMessage(), $ex->getCode());
			}
		}
	}
	
	public function delete($table, $where = '')
	{
		$query = sprintf('DELETE FROM %s', $table);
		
		if(false == empty($where) && isset($where))
		{
			if(true == is_array($where))
			{
				$data = array();
				foreach($where as $key => $value)
				{
					$data[] = sprintf('%s=%s', $key, $this->qouteValue($value));
				}
				$where = implode(', ', $where);
			}
			$query .= sprintf(' WHERE %s', ltim($where, ','));
		}
		
		$this->query( $query );
		return $this->getAffectedRows();
	}
	
	/**
	 * Qoute refferenced value
	 */
	public function qouteValue(&$value)
	{
		switch($value)
		{
			case is_null($value):
				$value = 'NULL';
				break;
			case is_string($value):
				$value = sprintf("'%s'", $value);
				break;
			case is_int($value):
				$value = sprintf("%d", $value);
			default:
				$value = $value;
		}
	}
	
	public function fetch($mode = PDO::FETCH_ASSOC)
	{
		if(null !== $this->result || false == is_null($this->result))
		{
			if(false !== ($result = $this->result->fetch($mode)))
			{
				return $result;
			}
		}
		return false;
	}
	
	public function getInsertID()
	{
		$id = ( $this->connection->lastInsertId );
		return isset($id) ? $id : NULL;
	}
	
	public function countRows()
	{
		return $this->result->fetchColumn();
	}
	
	public function getAffactedRows()
	{
		$affected = ($this->result->rowCount());
		return isset($affected) ? $affected : 0;
	}
}
