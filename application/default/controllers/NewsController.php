<?php

/**
 * NewsController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class NewsController extends BaseController {
	
	function init() {
		parent::init();
		$this->_setNeedsParams(array('topic'));
		$topics = $this->_getResults('_digg','getRandomStories');
		$rightPanel['diggNews'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'digg-news','news'=>$topics));
		$this->view->rightPanel = $rightPanel;
		$this->view->topics = $this->_getResults('_digg','getTopics');
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$rightPanel['amazon'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'recommended'));
		$this->view->rightPanel = $rightPanel;
	}
	
	function topicAction() {
		$topic = $this->_request->getParam('topic');
		$this->view->news = $this->_handleCacheResult("diggNews_{$topic}",'_digg','getByTopic',$topic);
		if(empty($this->view->news)) {
			$this->_redirect('/index');
		}
		$this->render('index');
	}
}