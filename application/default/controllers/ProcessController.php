<?php

/**
 * ProcessController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class ProcessController extends Zend_Controller_Action {
	
	function init() {
		if (!method_exists($this->getRequest(),'getOptions')) {
			throw new Zend_Exception('This actions cannot be called from a browser.');
		}
	}
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated ProcessController::indexAction() default action
	}
	
	function testAction() {
		echo "This is the Projess test action. \nIt simple returns parameters passed in...\n";
		foreach ($this->getRequest()->getOptions() as $key=>$value) {
			echo $key .":" .$value;
		}
	}

	function cacheCleanAction() {
		if ($this->getRequest()->getOption('all')) {
			Globals::getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
		} else {
			Globals::getCache()->clean(Zend_Cache::CLEANING_MODE_OLD);
		}
	}
}

