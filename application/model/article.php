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
 * Article Object Model
 */
class Article
{
    protected $id;
    protected $title;
    protected $author;
    protected $imagePreview;
    protected $summary;
    protected $content;
    protected $category;
    protected $tags = array();
    
    /**
     * Article constructer
     * @param array $entity - Article data. 
     */
    public function __construct(array $entity)
    {
        $this->id       = $entity['id'];
        $this->title    = $entity['title'];
        $this->author   = $entity['author'];
        $this->imagePreview = $entity['imagePreview'];
        $this->summary  = $entity['summary'];
        $this->content  = $entity['content'];
        $this->tags     = $entity['tags'];
    }
    
    /**
     * set the articles id
     * @param int $id - the article id
     */
    public function setID($id)
    {
        $this->id = $id;
    }
    
    /**
     * get the articles id
     * @return int - the article id
     */
    public function getID()
    {
        return $this->id;
    }
    
    /**
     * set the title of the article
     * @param string $title - article title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * get the title of the article
     * @return string - the article title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * set the author of the article
     * @param string $author - The authors name
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    /**
     * get the authors name
     * @return string - The authors name
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    /**
     * set the articles image
     * @param string $source - the image location for the article, ex: [domain]/public/images/articles/[article:id].jpeg 
     */
    public function setImagePreview($source)
    {
        $this->imagePreview = $source;
    }
    /**
     * get the articles image
     * @return string - the image location
     */
    public function getImagePreview()
    {
        return $this->imagePreview;
    }
    
    /**
     * set the summary for the article
     * @param string $summary - the short story for the article 
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }
    /**
     * get the summary of the article
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }
    
    /**
     * set the main content of the article
     * @param string $content - the article's main content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
    /**
     * get the main content of the article
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * set the category for the article
     * @param array $category - an array of categorys the article belongs to
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
    
    /**
     * get the categorys the article belongs to
     * @return array
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * set tags for the article
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }
    
    /**
     * get tags for the article
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }
}
