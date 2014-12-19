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
 * Windows Cache Driver
 */

class WinCacheDriver implements CacheDriverInterface
{
	public function __construct()
	{
		if(false == extension_loaded('wincache'))
		{
			throw new Exception('wincache extension is not loaded.');
		}
	}
	
	public function create($key, $value)
	{
		try {
			return wincache_ucache_set($key, $value);
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function read($key)
	{
		try {
			$cached == wincache_ucache_get($key, $result);
			
			if(false == $result)
			{
				return false;
			}
			return $result;
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function remove($key)
	{
		try {
			return wincache_ucache_delete($key);
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
	}
	
	public function update($key, $value)
	{
		$this->create($key, $value);
	}
}
