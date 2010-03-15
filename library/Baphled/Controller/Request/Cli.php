<?php

class Baphled_Controller_Request_Cli extends Zend_Controller_Request_Abstract {
	protected $_getopt;
	protected $_pathInfo;
	
	function __construct($rules=array()) {
		if (count($rules)>0) {
			$this->_getopt = new Zend_Console_Getopt($rules);
			
			$this->_getopt->parse();
			
			$this->_getOptions($this->_getopt->getOption($this->_moduleKey),'_module');
			$this->_getOptions($this->_getopt->getOption($this->_controllerKey),'_controller');
			$this->_getOptions($this->_getopt->getOption($this->_actionKey),'_action');
		}
	}
	
	protected function _getOptions($option,$value) {
		if (!is_null($option)) {
			$this->{$value} = $option;
		}
	}
	
	function getOption($key) {
		return $this->_getopt->getOption($key);
	}
	
	function getOptions() {
		$keyList = $this->_getopt->getOptions();
		$returnValue = array();
		foreach ($keyList as $key) {
			$returnValue[$key] = $this->_getopt->getOption($key);
		}
		return $returnValue;
	}
	
	function setPathInfo($pathInfo = null) {
		$this->_pathInfo = $pathInfo;
	}
	
	function getPathInfo() {
		return $this->_pathInfo;
	}
}