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
 * Session Wrapper
 */
class Session
{
	protected static $maxAge = 1800;
	protected static $method = 'whirlpool';
	protected static $data	 = array();
	
	public static function __callStatic($function, $args = array())
	{
		self::initialize();
		call_user_func_array(array(self, $function), $args);
	}
	
	public static function initialize()
	{
		if(ini_get('session.hash_function') != self::$method)
		{
			ini_set('session.hash_function', self::$method);
		}
		
		if(!session_id())
		{
			session_start();
		}
		
		self::$data &= ($_SESSION = self::$data);
	}
	
	public static function write($key, $value)
	{
		if(!is_string($key))
			throw new Exception("The session key must be a string");
			
		$key = self::$data[ $key ] = ($value);
		
		return $key;
	}
	
	public static function read($key, $child = false)
	{
		if(!is_string($key))
			throw new Exception("The session key must be a sting");
			
		if(isset(self::$data[$key]))
		{
			if(false === $child)
			{
				return self::$data[$key];
			} else if(isset(self::$data[$key][$child])) {
				return self::$data[$key][$child];
			}
		}
		return false;
	}
	
	public function delete($key)
	{
		if(isset(self::$data[$key]))
			unset(self::$data[$key]);
	}
	
	/**
	 * For Testing Purposes Only
	 */
	public static function dump()
	{
		echo nl2br(print_r(self::$data));
	}
	
	private static function age()
	{
		$last = self::read('session_last_active');
		
		if(false !== $last && (time() - $last > self::$maxAge))
		{
			self::destroy();
		}
		
		self::write('session_last_active', time());
	}
	
	public static function params()
	{
		$data = array();
		if('' !== session_id())
		{
			$data = session_get_cookie_params();
		}
		return $data;
	}
	
	public static function destroy()
	{
		if('' !== session_id())
		{
			self::$data = array();
			
			if(ini_get('session.use_cookies'))
			{
				$data = self::params();
				setcookie(session_name(), '', time() - 42000, $data['path'], $data['domain'], $data['secure'], $data['httponly']);
			}
			
			session_destroy();
		}
	}
}
