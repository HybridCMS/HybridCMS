<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */
  
# Hybrid Namespace
namespace Hybrid\Application\Config;

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
 * Database Configuration
 */
return array(
	'type'	=> 'mysql',
	
	'hostname'	=> '127.0.0.1',
	'username'	=> 'root',
	'password'	=> '',
	'database'	=> 'hybrid_bare',
);
