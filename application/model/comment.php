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
 * Comment Object Model
 */
class Comment
{
    protected $id;
    protected $article;
    protected $author;
    protected $votes;
    protected $timestamp;
    
    public function __construct(array $entity)
    {
        $this->id        = $entity['id'];
        $this->article   = $entity['article'];
        $this->author    = $entity['author'];
        $this->votes     = $entity['votes'];
        $this->timestamp = $entity['timestamp'];
    }
    
    public function setID($id)
    {
        $this->id = $id;
    }
    public function getID()
    {
        return $this->id;
    }
    
    public function setArticle($parent)
    {
        $this->article = $parent;
    }
    public function getArticle()
    {
        return $this->article;
    }
    
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    
    public function setTimestamp($datetime)
    {
        $this->timestamp = $datetime;
    }
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
