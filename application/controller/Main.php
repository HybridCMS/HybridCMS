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

use Hybrid\Application\View\frontpageView;

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
 * Hybrid Main Controller
 */
 
class Main extends AbstractController
{
	protected $content = array();
	
	public function __construct()
	{
		parent::__construct();
		
		if(true == ($result = $this->database->select('hybrid_config', array('key' => 'hotel.name'), 'value')))
		{
			$title = $this->database->fetch(PDO::FETCH_NUM);
			$this->content['title'] = $title[0];
		}
		if(true == ($result = $this->database->select('hybrid_config', array('key' => 'hotel.theme'), 'value')))
		{
			$theme = $this->database->fetch(PDO::FETCH_NUM);
			$this->content['theme'] = $theme[0];
		}
		if(true == ($result = $this->database->select('hybrid_config', array('key' => 'hotel.url'), 'value')))
		{
			$url   = $this->database->fetch(PDO::FETCH_NUM);
			$this->content['url']   = $url[0];
		}
	}
	public function frontpageView()
	{
		$this->template = new frontpageView();
		$this->template->setTitle( $this->content['title'] . ' - Frontpage' );
		
		$content = file_get_contents( sprintf('%s/../../public/template/%s/frontpage.html', dirname(__FILE__), $this->content['theme']) );
		$this->template->setContent( $content );
		
		echo "Hello";
		exit;
		
		return $this->template->toString();
	}
}
