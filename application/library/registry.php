<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */
  
# Hybrid Namespace
namespace Hybrid\Application\Library;

use Countable;
use Exception;

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
 * HybridCMS Registery
 */
class Registry implements Countable
{
	protected static $instance = null;
	protected $properties = array();
	
	public static function getInstance()
	{
		if(false == (self::$instance instanceof self))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __get($name)
	{
		if(isset($this->properties[$name]))
			return $this->properties[$name];
		
		return false;
	}
	public function __set($name, $value)
	{
		$this->properties[$name] = $value;
	}
	public function count()
	{
		return count($this->properties);
	}
}
