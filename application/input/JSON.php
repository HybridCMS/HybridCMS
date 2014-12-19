<?php
/**
 *	&HybridCMS
 *	CMS (Content Management System) for Habbo Emulators.
 *
 *	@author     GarettMcCarty <mrgarett@gmail.com> DB:GarettisHere
 *	@version    1.0.0
 *	@link       http://github.com/GarettMcCarty/HybridCMS
 *	@license    Attribution-NonCommercial 4.0 International
 */

namespace Hybrid\Application\Input;

use Exception;

use Hybrid\Application\Library\Configuration;

if(!defined('HybridSecure'))
{
    if(class_exists('Configuration', true) !== false)
    {
        try {
            $application = Configuration::get('app');
            if(isset($application['url']))
            {
                $location = sprintf('Location: %s/404', $application['url']);
                header($location);
                unset($application);
            }
        } catch(Exception $ex) {}
    }
    echo 'Sorry a internal application error has occurred.';
    $error = sprintf('[AUTH] The file %s was denied access', basename(__FILE__));
    error_log($error);
    exit;
}

/**
 * Hybrid JSON Wrapper
 */
class JSON
{
	/**
	 * Encode Array/Object to JSON format
	 * 
	 * @var	object|array $object - The data to turn into JSON array.
	 * @out string
	 */
    public static function encode($object)
    {
		$json = json_encode($object);
		
		if(json_last_error())
		{
			throw new Exception('JSON encode error');
		}
		return $json;
	}
	
	/**
	 * Decode JSON string to a PHP Object
	 * @var string $json - Are JSON data.
	 * @out object
	 */
	public static function decode($json)
	{
		$object = json_decode($json);
		
		if(json_last_error())
		{
			throw new Exception('JSON decode error');
		}
		return $object;
	}
	
}	
