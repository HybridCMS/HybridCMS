<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */
  
# Hybrid Namespace
namespace Hybrid\Application\Library\Cache;

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
 * APC Driver Wrapper
 */
class APCDriver implements CacheDriverInterface
{
	public function __construct()
	{
		if(false == extension_loaded('apc'))
		{
			throw new Exception('APC Driver is not loaded.');
		}
	}
	
	public function create($key, $value)
	{
		try {
			if(false == apc_exists($key))
			{
				return apc_store($key, $value);
			}
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function read($key)
	{
		try {
			if(false != apc_exists($key))
			{
				return apc_fetch($key);
			}
			return false;
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function remove($key)
	{
		try {
			return apc_delete($key);
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function update($key, $value)
	{
		try {
			return apc_store($key, $value);
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
}
