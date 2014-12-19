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
 * Frontpage View
 */
 
class frontpageView extends AbstractView
{
	protected $data = array();
	
	public function __construct()
	{
		parent::__constuct();
	}
	
	/**
	 * Frontpage Object to Database Object.
	 */
	public function toDatabase()
	{
		
	}
	
	/**
	 * Frontpage HTML string.
	 */
	public function toString()
	{
		# Header
		$this->data[] = implode( $this->renderHeader() );
		# Content
		$this->data[] = implode( $this->renderContent() );
		# Footer
		$this->data[] = implode( $this->renderFooter() );
		
		return implode( $this->data );
	}
}
