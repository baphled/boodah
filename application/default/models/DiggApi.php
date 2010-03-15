<?php
/**
 * DiggApi
 * 
 * Basic Digg API used to retrieve news
 * from diggs website
 * 
 * @author Yomi (baphled) Colledge
 * @version $Id$
 *
 */
class DiggApi extends Baphled_Http_Api {
	
	/**
	 * @var String $_apiKey		API key used to interact with Digg
	 */
	protected $_apiKey;
	
	function __construct($user) {
		parent::__construct($user);
		$this->_apiKey = urlencode('http://boodah.net');
	}
	
	/**
	 * Get the users dugg stories
	 * @return Array	stories		An array of stories the user dugg
	 *
	 */
	function getDugg() {
		$url = "http://services.digg.com/user/{$this->_user}/dugg?appkey={$this->_apiKey}&type=json";
		return $this->_getJsonResponse($url)->stories;
	}
	
	/**
	 * Get stories of a specific topic
	 * 
	 * Retrieves a list of stories related to 
	 * the specified topic
	 *
	 * @param 	String 		$topic		A topic to search
	 * @return 	Array		stories		A list of stories
	 * 
	 */
	function getByTopic($topic) {
		try {
			$url = "http://services.digg.com/stories/topic/{$topic}?appkey={$this->_apiKey}&type=json";
			return $this->_getJsonResponse($url)->stories;
		} catch (Zend_Exception $e) {
			// @todo need to log
		}
		return false;
	}
	
	function getTopics() {
		try {
			$url = "http://services.digg.com/topics?appkey={$this->_apiKey}&type=json";
			return $this->_getJsonResponse($url)->topics;
		} catch (Zend_Exception $e) {
			// @todo need to log
		}
		return false;
	}

	function getRandomTopic() {
		$result = $this->getTopics();
		$rand = rand(0,count($result));
		return $result[$rand]->short_name;
	}

	function getRandomStories() {
		$topic = $this->getRandomTopic();
		return $this->getByTopic($topic);
	}
}