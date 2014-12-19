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
 * Hybrid View Class
 */
abstract class AbstractView
{
	protected $page = null;
	
	public function render(PageInstance $page)
	{
		$this->page = $page;
		
		return $this->toString();
	}
	
	/**
	 * Render Meta Tags
	 */
	private function renderMeta()
	{
		$data = array(' ');
		foreach($page->getMeta() as $name => $content)
		{
			if(true == ($name = 'http-equiv'))
			{
				$data[] = sprintf('<meta http-quiv="%s" content="%s" />', $content[0], $content[1]) . PHP_EOL;
				continue;
			}
			$data[] = sprintf('<meta name="%s" content="%s" />', $name, $content) . PHP_EOL;
		}
		return implode($data);
	}
	/**
	 * Render Content Header
	 */
	private function renderHeader()
	{
		$data = array();
		
		$data[] = '<!DOCTYPE html>'.PHP_EOL;
		$data[] = '<html lang="en">'.PHP_EOL;
		$data[] = '    <head>'.PHP_EOL;
		# Header Content
		$data[] = sprintf('        <title>%s</title>', $this->page->getTitle()).PHP_EOL;
		# Get Meta tags
		$data[] = $this->renderMeta();
		# Get Stylesheet Files
		foreach($this->page->getStylesheet() as $location)
		{
			$data[] = sprintf('        <link href="%s" type="text/css" />', $location) . PHP_EOL;
		}
		
		# End Header Content
		$data[] = '    </head>'.PHP_EOL;
	}
	/**
	 * Render Content Data
	 */
	private function renderContent()
	{
		# Content Data
		$data = array();
		
		# Body Start
		$data[] = '    <body>';
		
		foreach($this->page->getContent() as $block)
		{
			if(true == is_array($block))
			{
				$data[] = implode($block) . PHP_EOL;
				continue;
			}
			$data[] = $block . PHP_EOL;
		}
		
		$data[] = '    </body>';
		# Body End
		
		return ($data);
	}
	/**
	 * Render Footer Data
	 */
	private function renderFooter()
	{
		# Footer Content
		$data = array();
		
		$data[] = '<!-- Powered By HybridCMS -->' . PHP_EOL;
		
		# Javascript Source Files.
		foreach($this->page->getJavascript() as $src)
		{
			$data[] = sprintf('        <script src="%s" type="text/javascript"></script>', $src).PHP_EOL;
		}
		
		
		# End Body Page.
		$data[] = '    </body>'.PHP_EOL;
		# End HTML Page.
		$data[] = '</html>'.PHP_EOL;
		
		return ($data);
	}
	
	/**
	 * Build Database Object
	 */
	abstract public function toDatabase();
	/**
	 * Build HTML String
	 */
	abstract public function toString();
}
