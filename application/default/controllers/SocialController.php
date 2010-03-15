<?php

/**
 * SocialController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class SocialController extends BaseController  {
	
	function init() {
		parent::init();
		$this->view->title = "Baphled's Timeline";
		$this->view->headTitle('Social');
		$rightPanel['tweets-favs'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'fav-tweets','tweets'=>$this->view->favTweets));
        $rightPanel['tweets'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'tweets','tweets'=>$this->view->friendTweets));
        
        // @todo Messy need to cleanup
        $menu = array(
			array('link'=>'/social/news','title'=>'News','text'=>'News'),
			array('link'=>'/social/tweets/favourites','title'=>'Favourite Tweets','text'=>'Favourite Tweets'),
			array('link'=>'/social/tweets/user','title'=>'Baphled Tweets','text'=>'Baphled Tweets'),
			array('link'=>'/social/tweets/friends','title'=>'Followers Tweets','text'=>'Friends Tweets')
			);
		
		$this->view->subMenu = $menu;
        $this->view->rightPanel = $rightPanel;
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
	}
	
	function newsAction() {
		$this->view->title = 'Dugg News';
		echo $this->view->partial('/common/_news.phtml',
				array('news'=>$this->_getResults('_digg','getDugg')));
		$this->render('index');
	}
	
	function tweetsAction() {
		$param = $this->_request->getParam('tweet');
		if(empty($param)) {
			$this->_redirect('/social');
		}
		switch ($param) {
			case 'user':
				$call = 'userTweets';
				$this->view->title = "Baphled's Timeline";
				break;
			case 'friends':
				$call = 'friendsTweets';
				$this->view->title = "Friends Timeline";
				break;
			case 'favourites':
				$call = 'favourites';
				$this->view->title = "Favourites";
				break;
			default:
				$call = 'userTweets';
				$this->view->title = "Baphled's Timeline";
				break;
		}
		foreach ($this->_getResults('_twitter',$call) as $tweet) {
			echo $this->view->partial('/common/_tweet.phtml',array('tweet'=>$tweet));
		}
		$this->render('timeline');
	}
}