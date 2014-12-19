<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */

# Hybrid Namespace
namespace Hybrid\Application\Model\Mapper;

use Exception;
use RuntimeException;
use InvalidArgumentException;

use Hybrid\Application\Library\Configuration;

use Hybrid\Application\Library\Database\Adapter;
use Hybrid\Application\Library\Database\AdapterInterface;

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
 * Abstract Database Mapper
 */
abstract class AbstractMapper implements MapperInterface
{
	protected $adapter	= null;
	protected $table	= '';
	protected $class	= null;
	
	public function __construct(array $options)
	{
		if(false == ($this->adapter instanceof AdapterInterface))
		{
			$this->adapter = new Adapter();
		}
		
		if(false == is_array($options))
		{
			throw new RuntimeException('AbstractMapper $options can not be empty.');
		}
		
		if(false == isset($options['table'], $options['class']))
		{
			throw new RuntimeException('AbstractMapper table || class is not defined.');
		}
		
		$this->table = $options['table'];
		$this->class = $options['class'];
	}
	
	public function getAdapter()
	{
		return $this->adapter;
	}
	
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}
	public function getTable()
	{
		return $this->table;
	}
	
	public function setClass($class)
	{
		$this->class = $class;
		return $this;
	}
	public function getClass()
	{
		return $this->class;
	}
	
	public function find($id, $conditions = array())
	{
		$where = array('id', $id);
		
		if(false == empty($conditions) || isset($conditions))
		{
			if(false == is_array($conditions))
			{
				throw new RuntimeException('$conditions must be an array.');
			}
			$where = array_merge($conditions, $where);
		}
		
		$this->adapter->select($this->table, $where);
		
		if(true == ($result == $this->adapter->fetch()))
		{
			return $this->createEntity((array)$result);
		}
		return false;
	}
	
	public function update($entity)
	{
		if(false == ($entity instanceof $this->class))
		{
			throw new InvalidArgumentException('The enttity ' . get_class($entity) . ' must be an instance of ' . get_class($this->class));
		}
		
		$id		= $entity->getID();
		$data	= $entity->toArray();
		unset($data['id']);
		
		return $this->adapter->update($this->table, $data, array('id', $id));
	}
	
	public function insert($entity)
	{
		if(false == ($entity instanceof $this->class))
		{
			throw new InvalidArgumentException('The enttity ' . get_class($entity) . ' must be an instance of ' . get_class($this->class));
		}
		return $this->adapter->insert($this->table, $entity->toArray());
	}
	
	public function delete($id, $column = 'id')
	{
		if(true == ($id instanceof $this->class))
		{
			$id = $id->getID();
		}
		return $this->adapter->delete($this->table, array($column, $id));
	}
	
	abstract protected function createEntity(array $data);
	abstract public function toArray();
	abstract public function __toString();
}
