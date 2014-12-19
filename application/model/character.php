<?php
 /**
  *	HybridCMS For Phoenix Emulator(s)
  * 
  * @author		GarettisHere
  * @version	1.0.0
  * @license	Attribute-NonCommercial 4.0 Internal License
  */

# Hybrid Namespace
namespace Hybrid\Application\Model;

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
 * Character Object Model
 */
class Character
{
    protected $parent;
    
    protected $username;
    protected $password;
    protected $motto;
    protected $credits;
    protected $pixels;
    
    protected $timeLastUsed;
    protected $timeCreated; 
    
    public function __construct(array $entity)
    {
        # Character Parent
        $this->parent   = $entity[ 'parent' ];
        
        # Character Name
        $this->username = $entity['username'];
        # Character Password
        $this->password = isset($entity['password']) ? $entity['password'] : null;
        # Character Motto
        $this->motto    = $entity['motto'];
        # Character Credits
        $this->credits  = $entity['credits'];
        # Character Pixels
        $this->pixels   = $entity['pixels'];
        # Character Last Login DateTime
        $this->timeLastUsed = $entity['timeLastUsed'];
        # Character Registration DateTime
        $this->timeCreated  = $entity['timeCreated'];
    }
    
    /**
     * Set Parents Email Address
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
    /**
     * Get Parents Email Address
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Set Username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * Get Username
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setPassword($password)
    {
		$this->password = $password;
	}
	public function getPassword()
	{
		return $this->password;
	}
    
    public function setMotto($motto)
    {
        $this->motto = (string) $motto;
    }
    public function getMotto()
    {
        return $this->motto;
    }
    
    public function setCredits($coins)
    {
        $this->credits = $coins;
    }
    public function getCredits()
    {
        return $this->credits;
    }
    
    public function setPixels($points)
    {
        $this->pixels = $points;
    }
    public function getPixels()
    {
        return $this->pixels;
    }
    
    public function setTimeLastUsed($datetime)
    {
        $this->timeLastUsed = $datetime;
    }
    public function getTimeLastUsed()
    {
        return $this->timeLastUsed;
    }
    
    public function setTimeCreated($datetime)
    {
        $this->timeCreated = $datetime;
    }
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }
    
    
    public function __toString()
    {
		return 'Character';
	}
}
