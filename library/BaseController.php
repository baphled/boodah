<?php
/**
 * BaseController
 * 
 * Used to centralised all our controller functionality
 * @author Yomi (baphled) Colledge
 * @version $Id$
 *
 */
class BaseController extends Zend_Controller_Action {
	
	protected $_cache;
	
	/**
	 * Allows us to set the actions that need parameters.
	 * 
	 * This method basic checks to see we have an action
	 * of x and checks to see if it is valid, if so we store them
	 * 
	 * @param Array $actions	Array of actions that need params
	 * 
	 */
	protected function _setNeedsParams($actions = null) {
		if(null == $actions || !is_array($actions)) {
			foreach ($actions as $action) {
				$action .='Action';
				if(!method_exists($this->_view->$action)) {
					throw new Exception('Action not recognised');
				}
			}
			throw new Zend_Exception('Invalid actions, needs to be an array');
		}
		$this->_actions = $actions;
	}
	
	/**
	 * Checks to see whether our action needs GET or POST params
	 * if it does and has none we redirect the user to the root of
	 * the controller
	 *
	 */
	function postDispatch() {
		$name = "{$this->_request->getParam('action')}Action";
		if(method_exists($this,$name)) {
			foreach ($this->_actions as $loopAction) {
				if ($name == "{$loopAction}Action" &&
						(false == $this->_request->isGet()) && 
						(false == $this->_request->isPost())) {
					$this->_redirect("/{$this->_request->getParam('controller')}");
				}
			}	
		}
	}
	
	function init() {
	  $config = Zend_Registry::get(Zend_SetupInit::$_env);
		$this->_actions = array();
		$this->_twitter = new Twitter($config->twitter->user, $config->twitter->pass);
    	$this->_github = new GitHubApi('baphled');
    	$this->_lighthouse = new LightHouseApi('baphled');
    	$this->_digg = new DiggApi('baphled');
    	
    	$this->_rightPanel = array();
    	$this->_cache = Globals::getCache();
    	$this->view->baseUrl = $this->_request->getBaseUrl();
    	$this->view->projects = $this->_getResults('_github','getProjects');
    	$this->view->friendTweets = $this->_getResults('_twitter','friendsTweets');
    	$this->view->favTweets = $this->_getResults('_twitter','favourites');
        $this->view->latestTweet = $this->_getResults('_twitter','latestTweet');
        
        $this->_rightPanel['projects'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'projects','projects'=>$this->view->projects));
        $this->_rightPanel['tweets'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'tweets','tweets'=>$this->view->friendTweets));
        
        $this->view->rightPanel = $this->_rightPanel;
        
        $menu = array('menuItems'=>
        			array(
        				array('url'=>'/','title'=>'Home'),
       					array('url'=>'/blog','title'=>'Blog'),
   						//array('url'=>'/news','title'=>'News'),
        				array('url'=>'/social','title'=>'Social'),
        				array('url'=>'/projects','title'=>'Projects'),
        				array('url'=>'/tracking','title'=>'Tracking')));
        						
        $this->view->menu = $menu;
        $this->view->subMenu = '';
	}

	/**
	 * Gets the most current results
	 * 
	 * Is a wrapper for _handleCacheResult
	 * as passing a unique cache name can be
	 * error prone
	 * 
	 * @todo Make a helper.
	 *
	 * @param 	String 	$object		Object that our method call belongs to
	 * @param 	String 	$method		Method we want to call
	 * @param 	String 	$args		Arguements passed to method
	 * @param 	String 	$cacheName	The desired name of the cache
	 * @return 	Mixed	$results	The most current results
	 * 
	 */
	function _getResults($object,$method,$args=null,$cacheName=null) {
		if (null == $cacheName) {
			return $this->_handleCacheResult($method,$object,$method,$args);
		}
		return $this->_handleCacheResult($cacheName,$object,$method,$args);
	}
	
	/**
	 * Handles our caching functionality
	 * 
	 * Is used to determine whether we have something cached
	 * if we do we retrieve it, otherwise we call the necessary function
	 *
	 * @param String $cacheTitle	The name of the cache
	 * @param String $object		The object to which the call is made
	 * @param String $method
	 * @param String $args
	 * @return mixed $result		Our result, cached or fresh if cache expires
	 * 
	 */
	function _handleCacheResult($cacheTitle,$object,$method,$args=null) {
		$cacheTitle = str_replace('-','_',$cacheTitle);
		if(!$result = Globals::getCache()->load($cacheTitle)) {
			if(null == $args) {
				$results = $this->$object->$method();
			} else {
				if(is_array($args)) {
					$results = $this->$object->$method(implode(',',$args));
				} else {
					$results = $this->$object->$method($args);
				}
			}
			$this->_cache->save($results,$cacheTitle);
		} else {
			$results = $result;
		}
		return $results;
	}
}