<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */
  
# Hybrid Namespace
namespace Hybrid\Application\Controller;

use Exception;
use PDO;
use PDOException;

use Hybrid\Application\Library\Database\Adapter;
use Hybrid\Application\Library\Database\AdapterInterface;
use Hybrid\Application\Library\Configuration;

use Hybrid\Application\View\View;
use Hybrid\Application\View\Page;

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
 * Abstract Controller
 */
abstract class AbstractController
{
	protected $template = null;
	protected $database = null;
	
	public function __construct()
	{
		if(false == ($this->database instanceof AdapterInterface))
		{
			$this->database = new Adapter();
		}
	}
	
	public function render($view, $params = array())
	{
		return $this->template->toString();
	}
}
