<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */

# Hybrid Namespace
namespace Hybrid\Application\View;

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
 * Hybrid\View Page Object
 */
class Page
{
	protected $title;
	protected $stylesheets = array();
	protected $javascripts = array();
	protected $meta = array();
	protected $body = array();
	
	public function __construct($title, $body = array())
	{
		$this->title = $title;
		$this->body  = $body;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setStylesheet($path)
	{
		$this->stylesheets[] = $path;
		return $this;
	}
	public function getStylesheet()
	{
		return $this->stylesheets;
	}
	
	public function setJavascript($src)
	{
		$this->javascripts[] = $src;
		return $this;
	}
	public function getJavascript()
	{
		return $this->javascripts;
	}
	
	public function setMeta($name, $content)
	{
		$this->meta[$name] = $content;
		return $this;
	}
	public function getMeta()
	{
		return $this->meta;
	}
	
	public function setContent($content)
	{
		$this->body[] = $content;
	}
	public function getContent()
	{
		return $body;
	}
}
