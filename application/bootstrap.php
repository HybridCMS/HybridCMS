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

use Hybrid\Application\Library\Autoloader;

use Hybrid\Application\Library\Database\Adapter;

use Exception;

use Hybrid\Application\Library\Configuration;
use Hybrid\Application\Library\Registry;
use Hybrid\Application\Library\Router;
use Hybrid\Application\Library\Session;

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
 * HybridCMS Bootstrap
 */
class Bootstrap
{
	protected static $instance = null;
	
	protected $adapter;
	protected $config;
	
	/**
	 * Get Bootstrap Instance
	 * @return object - returns an instance of Bootstrap
	 */
	public static function getInstance()
	{
		if(false == (self::$instance instanceof self))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Bootstrap constructor
	 */
	public function __construct()
	{
		error_reporting(-1);
		
		// application config
		require( dirname(__FILE__) . '/library/configuration.php' );
		$this->config = new Configuration();
		
		$app = $this->config->get('app');
		
		if(isset($app['error']) && true == $app['error'])
		{
			//error_reporting(E_ALL ^ E_NOTICE);
			
			if(true == (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'))
			{
				#openlog(dirname(__FILE__) . '/storage/logs/' . $app['error.file'], LOG_PID | LOG_PERROR, LOG_USER);
			} else {
				#openlog(dirname(__FILE__) . '/storage/logs/' . $app['error.file'], LOG_PID | LOG_PERROR, LOG_LOCAL0);
			}
		} else {
			error_reporting(0);
		}
		
		// application loader
		require_once( dirname(__FILE__) . '/library/autoloader.php' );
		Autoloader::register();
		
		// application database adapter
		if(true == file_exists('storage/install.lock'))
		{
			#return new Adapter();
		} else {
			Session::write('installer_active', true);	
		}
		require( dirname(__FILE__) . '/routes.php' );
	}
	
	public function __destruct()
	{
		
	}
}
