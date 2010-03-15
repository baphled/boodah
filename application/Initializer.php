<?php
/**
 * My new Zend Framework project
 * 
 * @author  
 * @version 
 */

require_once 'Zend/Controller/Plugin/Abstract.php';
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Request/Abstract.php';
require_once 'Zend/Controller/Action/HelperBroker.php';
require_once "Zend/Loader.php"; 

/**
 * 
 * Initializes configuration depndeing on the type of environment 
 * (test, development, production, etc.)
 *  
 * This can be used to configure environment variables, databases, 
 * layouts, routers, helpers and more
 *   
 */
class Initializer extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Config
     */
    protected $_general;

    /**
     * @var string Current environment
     */
    protected $_env;

    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    /**
     * @var string Path to application root
     */
    protected $_root;

    /**
     * Constructor
     *
     * Initialize environment, root path, and configuration.
     * 
     * @param  string $env 
     * @param  string|null $root 
     * @return void
     */
    public function __construct($root = null)
    {
        if (null === $root) {
            $root = realpath(dirname(__FILE__) . '/../');
        }
        $this->_root = $root;
        // Set up autoload.
		Zend_Loader::registerAutoload(); 
		Zend_SetupInit::setupInit();
        
        $this->_front = Zend_Controller_Front::getInstance();
        $this->_initErrorReporting();
		$tmz = Zend_ConfigSettings::setupTimeZone();
		date_default_timezone_set($tmz);
		
    }

 	private function _initErrorReporting() {
    	Zend_SetupInit::initErrorReporting();
    	// set the test environment parameters
        if (('local' | 'development') === Zend_SetupInit::$_env) {
			$this->_front->throwExceptions(true);  
        }
    }
    
    /**
     * Route startup
     * 
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
       	$this->initDb();
        $this->initHelpers();
        $this->initView();
        $this->initPlugins();
        $this->initRoutes();
        $this->initControllers();
    }
    
    /**
     * Initialize data bases
     * 
     * @return void
     */
    public function initDb()
    {
    	
    }

    /**
     * Initialize action helpers
     * 
     * @return void
     */
    public function initHelpers()
    {
    	// register the default action helpers
    	Zend_Controller_Action_HelperBroker::addPath('../application/default/helpers', 'Zend_Controller_Action_Helper');
    	Zend_Controller_Action_HelperBroker::addPrefix('Zend_Controller_Action_Helper');
    }
    
    /**
     * Initialize view 
     * 
     * @return void
     */
    public function initView()
    {
		// Bootstrap layouts
		Zend_Layout::startMvc(array(
		    'layoutPath' => $this->_root .  '/application/default/layouts',
		    'layout' => 'main'
		));
    	
    }
    
    /**
     * Initialize plugins 
     * 
     * @return void
     */
    public function initPlugins()
    {
    	
    }
    
    /**
     * Initialize routes
     * 
     * @return void
     */
    public function initRoutes()
    {
    	$news = array('module'=>'default',
    			  'controller'=>'news',
    			  'action'=>'topic');
    	$tags = array('module'=>'default',
    			  'controller'=>'blog',
    			  'action'=>'tag');
    	$milestones = array('module'=>'default',
    			  'controller'=>'tracking',
    			  'action'=>'milestone');
    	$projects = array('module'=>'default',
    			  'controller'=>'projects',
    			  'action'=>'info');
    	$tweets = array('module'=>'default',
    			  'controller'=>'social',
    			  'action'=>'tweets');
    	$ticket = array('module'=>'default',
    			  'controller'=>'tracking',
    			  'action'=>'ticket');
    	$tickets = array('module'=>'default',
    			  'controller'=>'tracking',
    			  'action'=>'tickets');
    	$newsRoute = new Zend_Controller_Router_Route(
    		'/news/topic/:topic', $news);
    	$tagsRoute = new Zend_Controller_Router_Route(
    		'/blog/tag/:tag', $tags);
    	$milestonesRoute = new Zend_Controller_Router_Route(
    		'/tracking/milestone/:milestone', $milestones);
    	$projectsRoute = new Zend_Controller_Router_Route(
    		'/projects/info/:project', $projects);
    	$ticketRoute = new Zend_Controller_Router_Route(
    		'/tracking/milestone/:milestone/ticket/:ticket', $ticket);
    	$ticketsRoute = new Zend_Controller_Router_Route(
    		'/tracking/milestone/:milestone/tickets', $tickets);
    	$tweetsRoute = new Zend_Controller_Router_Route(
    		'/social/tweets/:tweet', $tweets);
    	$router = $this->_front->getRouter();
    	$router->addRoute('topic',$newsRoute);
    	$router->addRoute('tag',$tagsRoute);
    	$router->addRoute('milestone',$milestonesRoute);
    	$router->addRoute('ticket',$ticketRoute);
    	$router->addRoute('ticket-milestone',$ticketsRoute);
    	$router->addRoute('project',$projectsRoute);
    	$router->addRoute('tweet',$tweetsRoute);
    	$this->_front->setRouter($router);
    }

    /**
     * Initialize Controller paths 
     * 
     * @return void
     */
    public function initControllers()
    {
    	$this->_front->addControllerDirectory($this->_root . '/application/default/controllers', 'default');
    }
}
?>
