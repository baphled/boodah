<?php

/**
 * BlogController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class BlogController extends BaseController {
	
	function init() {
		
		parent::init();
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$feed = new SimplePie('http://baphled.wordpress.com/feed');
		$feed->init();
		$this->view->description 	= $feed->get_description();
		$this->view->title 			= $feed->get_title();
		$this->view->items 			= $feed->get_items();
		$this->render('feed');
	}
	
	function tagAction() {
		$tag = $this->_request->getParam('tag');
		if(empty($tag)) {
			$this->_redirect('/index');
		}
		$feed = new SimplePie("http://baphled.wordpress.com/tag/{$tag}/feed");
		$feed->init();
		$this->view->description 	= $feed->get_description();
		$this->view->title 			= $feed->get_title();
		$this->view->items 			= $feed->get_items();
		$this->render('feed');
	}
}