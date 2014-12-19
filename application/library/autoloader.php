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
 * HybridCMS Autoloader
 */
class Autoloader
{
	public static function register()
	{
		spl_autoload_register('self::loader');
	}
	public static function unregister()
	{
		spl_autoload_unregister('self::loader');
	}
	
	public static function loader($className)
	{
		$className = ltrim($className, 'Hybrid\\');
		$className = strtolower($className);
		$fileName  = '';
		$namespace = '';
		
		if(false !== $lastNsPos = strrpos($className, '\\'))
		{
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName  = str_ireplace('\\', DIRECTORY_SEPARATOR, $className) . DIRECTORY_SEPARATOR;
		}
		exit;
		
		$fileName .= str_ireplace('_', DIRECTORY_SEPARATOR, $className) . '.php';
		
		if(true == file_exists($include = sprintf('%s/../../%s', dirname(__FILE__), $fileName)))
		{
			require( $include );
		} else {
			echo 'The file: ', dirname(__FILE__), '/../../', $fileName, ' could not be found!'.PHP_EOL;
			debug_print_backtrace();
			exit;
		}
	}
}
