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

use Exception;

use Hybrid\Application\Library\Router;

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

echo 'Test';

#
# HybridCMS Routes
#

# Frontpage
Router::GET('/', function ()
{
	echo 'frontpage echoed';
	return 'Hello World';
});
# Login
Router::GET('/login', function ()
{
	echo 'login echoed';
	return 'Login';
});
Router::POST('/login', function ()
{
	
});
# Logout
Router::GET('/logout', function ()
{
	Session::destroy();
	Router::RedirectTo('/');
});
# Register
Router::GET('/register', function ()
{
	
});
Router::POST('/register', function ()
{
	
});
# Client Pages
Router::GET('/client', function ()
{
	// TODO
});
Router::GET('/client/[string]', function ($error = null)
{
	switch($error)
	{
		case 'reload':
		default:
			Router::RedirectTo('/client');
			break;
	}
});
# Community Pages
Router::GET('/community/[string]', function ($page = null)
{
	switch($page)
	{
		case 'articles':
			break;
		case 'staff':
			break;
		default:
			// TODO:
	}
});
# Profiles
Router::GET('/profile/[int]', function ($id = null)
{
	/**
	 *  if($id = Adapter::select('users', array('id', $id), 'id', 1)):
	 * 		$character = new characterMap();
	 * 		return new View\Profile($character->find($id);
	 *  else if($id = Session::read('account_id')):
	 *		$character = new characterMap();
	 * 		return new View\Profile($character->find($id);
	 *  else:
	 * 		Router::RedirectTo('/');
	 *  endif;
	 */
});
Router::GET('/profile/[string]', function ($name = null)
{
	/**
	 *  if($id = Adapter::select('users', array('username', $name), 'id', 1)):
	 * 		$character = new characterMap();
	 * 		return new View\Profile($character->find($id));
	 * 	else if($id = Session::read('account_id')):
	 * 		$character = new characterMap();
	 * 		return new View\Profile($character->find($id));
	 *  else:
	 *		Router::RedirectTo('/');
	 * 	endif;
	 */
});
