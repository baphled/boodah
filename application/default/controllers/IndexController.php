<?php

/**
 * IndexController - The default controller class
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class IndexController extends BaseController {
	/**
	 * The default action - show the home page
	 */
    public function indexAction() 
    {
        $this->view->title = 'Home';
        $rightPanel['tweets-favs'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'fav-tweets','tweets'=>$this->view->favTweets));
        $rightPanel['tweets-friends'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'follow-tweets','tweets'=>$this->view->friendTweets));
        
        $this->view->rightPanel = $rightPanel;
    }
}
