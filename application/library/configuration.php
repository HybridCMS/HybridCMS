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
 * HybridCMS Configuration
 */
class Configuration
{
	protected static $cache = null;
	
	public static function init()
	{
		if(!self::$cache instanceof Cache)
		{
			# self::$cache = new Cache();
		}
	}
	public static function cache($config)
	{
		self::init();
		
		$file = array(
			'location'	=> sprintf('%s/../config/%s.php', dirname(__FILE__), $config),
		);
		$file['content'] = include($file['location']);
		
		try {
			$cache = self::$cache;
			#$cache->create($config, $file['content']);
		} catch( Exception $ex ) {
			throw new Exception($ex->getMessage(), $ex->getCode());
		}
		return false;
	}
	public static function get($config, $useCache = false)
	{
		if(true == $useCache)
		{
			$cache = self::$cache;
			return $cache->read($config);
		}
		$file = sprintf('%s/../config/%s.php', dirname(__FILE__), $config);
		return include($file);
	}
}
