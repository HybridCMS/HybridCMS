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
 * Cache Driver Interface for Cache Driver Wrappers
 */
interface CacheDriverInterface
{
	public function create($key, $value);
	public function read($key);
	public function update($key, $value);
	public function remove($key);
}
