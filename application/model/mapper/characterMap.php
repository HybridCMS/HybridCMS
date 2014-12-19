<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */

# Hybrid Namespace
namespace Hybrid\Application\Model\Mapper;

use Exception;
use RuntimeException;

use Hybrid\Application\Library\Configuration;

use Hybrid\Application\Model\Emulator\Emulator;

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
 * CharacterMap for Character/Accounts.
 */
class CharacterMap extends AbstractMapper
{
	protected $entity;
	protected $table	= 'users';
	protected $class	= '\Hybrid\Application\Model\Character';
	
	protected function createEntity(array $data)
	{
		$emulator = Emulator::fetch();
		
		$this->table = $emulator['fields']['character']['table'];
		
		$character	 = new $this->class(array(
			# Parent Email
			'parent'	=> $emulator['fields']['account']['email'],
			'password'  => $emulator['fields']['account']['pass'],
			
			'motto'		=> $emulator['fields']['character']['motto'],
			'credits'	=> $emulator['fields']['character']['credits'],
			'pixels'	=> $emulator['fields']['character']['pixels'],
			'timeLastUsed'	=> $emulator['fields']['character']['timeLastUsed'],
			'timeCreated'	=> $emulator['fields']['character']['timeCreated']
		));
		
		return ($this->entity = $character);
	}
	
	public function toArray()
	{
		/**
		 * Translation for Phoenix Emulator: 
		 * SQL Mockup: INSERT INTO users ( implode(',', array_keys()) ) VALUES ( implode(',', array_values()) );
		 * 
		 * 'mail' 			 => 'johndoe@emailaddress.com',
		 * 'password'		 => 'secretpassword',
		 * 
		 * 'motto'	 		 => 'I love HybridCMS!',
		 * 'credits' 		 => 3500,
		 * 'activity_points' => 75000,
		 * 'timeLastUsed'	 => new time()
		 * 'timeCreated'	 => DATETIME
		 */
		return array(
			$emulator['fields']['account']['email']		 	 => $entity->getParent(),
			$emulator['fields']['account']['pass']			 => $enitty->getPassword(),
			
			$emulator['fields']['character']['motto']		 => $entity->getMotto(),
			$emulator['fields']['character']['credits']		 => $entity->getCredits(),
			$emulator['fields']['character']['pixels']		 => $entity->getPixels(),
			$emulator['fields']['character']['timeLastUsed'] => $entity->getTimeLastUsed(),
			$emulator['fields']['character']['timeCreated']	 => $entity->getTimeCreated()
		);
		
	}
	public function __toString()
	{
		return 'characterMap';
	}
}
