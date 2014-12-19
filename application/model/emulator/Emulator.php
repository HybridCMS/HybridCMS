<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */

# Hybrid Namespace
namespace Hybrid\Application;

use Exception;

use Hybrid\Application\Library\Configuration;
use Hybrid\Application\Input\JSON;

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
 * Emulator Handler
 */
class Emulator
{
	public static function fetch($emulator = null)
	{
		if(true == is_null($emulator))
		{
			$app = Configuration::get('app');
			
			if(false == isset($app['emu']))
			{
				$app['emu'] = 'phoenix';
			}
			$emu = $app['emu'];
		}
		
		$data = null;
		
		if( true == (file_exists( $file = sprintf('%s/data/%s.json', dirname(__FILE__), $emu) )) )
		{
			$data = JSON::jsonToArray( file_get_contents( $file ) );
		}
		
		return $data;
	}
	
	public static function send($mus = null) { return null; }
}
