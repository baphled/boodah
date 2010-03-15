<?php
require_once dirname(__FILE__) .'/../../libs/TestHelper.php';
/**
 * DiggApi TestCase
 * 
 * Testcase to implement Digg API functionality
 *  
 * @author Yomi (baphled) Colledge
 * @version $Id$
 */
class DiggApiTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		$this->_api = new DiggApi('baphled');
		parent::setUp ();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		parent::tearDown ();
	}
	
	function testApiIsNotNull() {
		$this->assertNotNull(new DiggApi('baphled'));
	}
	
	function testApiHasExpectedProperties() {
		$this->assertClassHasAttribute('_apiKey','DiggApi');
		$this->assertClassHasAttribute('_user','DiggApi');
	}
	
	function testGetDuggReturnsAnArray() {
		$this->assertType('array',$this->_api->getDugg());
	}
	
	function testWhatHappensWhenWePassAClientThatDoesntExist() {
		$this->setExpectedException('Zend_Exception');
		$api = new DiggApi('1');
		$api->getDugg();
	}
	
	function testGetDuggReturnsThePropertiesWeExpect() {
		$results = $this->_api->getDugg();
		foreach ($results as $result) {
			$this->_assertStories($result);
		}
	}
	
	function _assertStories($result) {
		$this->assertNotNull($result->link);
		$this->assertNotNull($result->submit_date);
		$this->assertNotNull($result->description);
		$this->assertNotNull($result->title);
		$this->assertNotNull($result->media);
		$this->assertNotNull($result->user->name);
		$this->assertNotNull($result->user->icon);
		$this->assertNotNull($result->topic->name);
		$this->assertNotNull($result->topic->short_name);
		$this->assertNotNull($result->container->name);
		$this->assertNotNull($result->container->short_name);
	}
	
	function testGetTopicIsNotNull() {
		$this->assertNotNull($this->_api->getByTopic('programming'));
	}
	
	function testGetTopicReturnsFalseIfTopicIsNotFound() {
		$this->assertFalse($this->_api->getByTopic('progam'));
	}
	
	function testGetTopicReturnsAnArray() {
		$this->assertType('array',$this->_api->getByTopic('programming'));
	}
	
	function testGetTopicReturnsTheExpectedProperties() {
		foreach ($this->_api->getByTopic('programming') as $story) {
			$this->_assertStories($story);
		}
	}
	
	function testGetTopicsReturnsAnArrayOfTopics() {
		$result = $this->_api->getTopics();
		$this->assertType('array',$result);
	}
	
	function testGetTopicsReturnsTheExpectedProperties() {
		$results = $this->_api->getTopics();
		foreach ($results as $result) {
			$this->assertNotNull($result->name);
			$this->assertNotNull($result->short_name);
			$this->assertNotNull($result->container->name);
			$this->assertNotNull($result->container->short_name);
		}
	}
	// @todo create units for functionality allowing us to retrieve
	// stories by random topics.
	
	function testGetRandomTopicReturnsARandomTopicEachIteration() {
		$results = $this->_api->getTopics();
		$rand = rand(0,count($result));
		$this->assertNotNull($results[$rand]->short_name);
	}
	
	function testGetRandomTopicIsDiffFromPrevious() {
		$last = '';
		for($i=0;$i<5;$i++) {
			$result = $this->_api->getRandomTopic();
			$this->assertNotEquals($last,$result);
			$last = $result;
		}
	}
	
	function testGetRandomTopicStories() {
		$result = $this->_api->getRandomStories();
		$this->assertNotNull($result);
	}
}