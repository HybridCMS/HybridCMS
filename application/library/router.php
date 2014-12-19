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
 * HybridCMS Router
 */
class Router
{
	protected static $regix	 = array('[string]' => '([^/]+)', '[int]' => '(\d+)', '[*]' => '(.*?)');
	protected static $routes = array();
	
	public static function GET($route, $callback)
	{
		return self::addRoute('GET', $route, $callback);
	}
	public static function POST($route, $callback)
	{
		return self::addRoute('POST', $route, $callback);
	}
	
	public static function addRoute($method = 'GET', $route, $request)
	{
		echo '<!-- ', $route, ' -->' . PHP_EOL;
		
		$route = str_ireplace(array_keys(self::$regix), array_values(self::$regix), $route);
		self::$routes[$route] = sprintf('~^/index.php%s$~', $route);
		
		echo '<!-- ', self::$routes[$route], ' -->';
		
		$requestUri = filter_input(INPUT_SERVER, 'REQUEST_URI');
		
		if(false == strpos($requestUri, 'index.php'))
		{
			$requestUri = sprintf('/index.php%s', $requestUri);
		}
		
		if($method != filter_input(INPUT_SERVER, 'REQUEST_METHOD'))
		{
			echo '<!-- wrong method for : ' . $requestUri . ' -->'.PHP_EOL;
			return;
		}
		
		if(true == array_key_exists($route, self::$routes))
		{
			if(false !== preg_match(self::$routes[$routes], $requestUri, $matches))
			{
				if(count($matches) > 0 && $matches[0] == $requestUri)
				{
					if(true == is_callable($request))
					{
						if(isset($matches[1]))
						{
							echo $request($matches[1]);
						} else {
							echo $request();
						}
					} else if(is_string($request)) {
						if(false !== stripos($request, '@'))
						{
							$data = explode('@', $request);
							$controller = "\Hybrid\Application\Controller\{$data[0]}";
							if(isset($matches[1]) && isset($data[1]))
							{
								$matches = array_shift($matches);
								call_user_func_array(array(new $controller, $data[1]), $matches);
							} else {
								call_user_func(array(new $controller, $data[1]));
							}
						} else {
							throw new \IllegalArgumentException('Route Request for an string must be in this syntax "controllerName@functionName".');
						}
					} else if(is_array($request)) {
						if(isset($request[0]))
						{
							# [[controller] => [function], [arguments]]
							try {
								if(isset($request[1]))
								{
									if(!is_array($request[1]))
									{
										$request[1] = array($request[1]);
									}
									call_user_func_array(array(new array_keys($request[0]), array_values($request[0])), $request[1]);
								} else {
									call_user_func(array(new array_keys($request[0]), array_values($request[0])));
								}
							} catch( Exception $ex ) {
								throw new Exception($ex->getMessage(), $ex->getCode());
							}
						} else {
							throw new \IllegalArgumentException('Route Request for an array must have a controller and a function call.');
						}
					} else {
						throw new \IllegalArgumentException('Route Request is not supported must be an array, string or callable function.');
					}
				}
			}
		}
	}
	
	public static function RedirectTo($location)
	{
		$application = \Hybrid\Application\Library\Configuration::get('app');
		
		if(false !== strpos($location, 'http://') || false !== strpos($location, 'https://'))
		{
			if(!headers_sent())
			{
				header( sprintf('Location: %s', $location) );
				exit;
			} else {
				echo '<meta http-equv="refresh" content="; ', $application['url'], $location, '" />';
				echo '<center><br /><br />Redirecting in 5 seconds to <a href="', $location, '">', $location, '</a></center>';
				exit;
			}
		}
	}
}
