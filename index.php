<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */
 
# Hybrid Authication
defined('HybridSecure') or define('HybridSecure', true);

# Hybrid Alias
use Hybrid\Application as Engine;

# Hybrid Bootstrap
try {
	
	require( dirname(__FILE__) . '/application/bootstrap.php' );
	$console = new Engine\Bootstrap();
	
} catch( Exception $ex ) {
	echo 'Error: ', $ex->getMessage(), '<br />' . PHP_EOL;
	echo 'File: ', $ex->getFile(), '<br />' . PHP_EOL;
	echo 'Line: ', $ex->getLine();
}