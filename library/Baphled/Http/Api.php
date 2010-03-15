<?php
/**
 * Basic Http Wrapper 
 * 
 * Used to retrieve our HTTP responses.
 * 
 * @author Yomi (baphled) Colledge
 * @version $Id$
 */
class Baphled_Http_Api {
	
	protected $_user;
	
	protected $_config;
	
	function __construct($user=null) {
		if(null === $user) {
			throw new Zend_Exception('Username must be passed!');
		}
		Zend_SetupInit::setupInit();
		$this->_config = Zend_Registry::get(Zend_SetupInit::$_env);
		$this->_user = $user;
	}
	
	/**
	 * Check our response is successful
	 * 
	 * Throws an exception is the response is unsuccessful
	 * 
	 * @property $response	Zend_Http_Client
	 * 
	 */
	private function _checkResponse($response) {
		if(200 !== $response->getStatus()) {
			throw new Zend_Exception('Client is not registered to api.');
		}
	}
	
	/**
	 * Validate a projects name
	 * 
	 */
	protected function _validateProjectName($project) {
		if(null == $project) {
			throw new Zend_Exception('No project name given.');
		}
	}
	
	/**
	 * Gets our JSON response
	 * 
	 * @param  String		$url		Url to query
	 * @return Zend_Json	$result		Our JSON response
	 * 
	 */
	protected function _getJsonResponse($url) {
		$client = new Zend_Http_Client($url);
		$response = $client->request();
		$this->_checkResponse($response);
		return Zend_Json::decode($response->getBody(), Zend_Json::TYPE_OBJECT);
	}
}
?>