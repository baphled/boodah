<?php
require_once dirname(__FILE__) .'/../../libs/TestHelper.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
 *  test case.
 */
class TwitterTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->_twitter = new Twitter(); 
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		parent::tearDown ();
	}
	
	function testConstructor() {
		$this->assertNotNull($this->_twitter);
		$this->assertType('Twitter',$this->_twitter);
	}
	
	function testTwitterHasTwitterProperty() {
		$this->assertClassHasAttribute('_twitter','Twitter');
	}
	
	function testTwitterCanRetrieveOurLatestTweet() {
		$result = $this->_twitter->latestTweet();
		$this->assertType('string',$result);
		$this->assertNotEquals('',$result);
	}
	
	function testTwitterCanRetrieveOurFollowers() {
		$result = $this->_twitter->followers();
		$this->assertNotEquals('',$result);
	}
	
	function testTwitterCanRetrieveOurFriends() {
		$result = $this->_twitter->friends();
		$this->assertNotNull('',$result);
	}
	
	function testTwitterCanRetrieveFavourites() {
		$result = $this->_twitter->favourites();
		$this->assertType('array',$result);
		$this->assertLessThanOrEqual(20,count($result));;
	}
	
	function testTwitterCanRetrieveFavouritesWithExpectedArrayKeys() {
		$result = $this->_twitter->favourites();
		$this->assertArrayHasKey('nick',$result[0]);
		$this->assertArrayHasKey('name',$result[0]);
		$this->assertArrayHasKey('msg',$result[0]);
		$this->assertArrayHasKey('sent',$result[0]);
		$this->assertArrayHasKey('location',$result[0]);
		$this->assertArrayHasKey('created_at',$result[0]);
		$this->assertArrayHasKey('description',$result[0]);
		$this->assertArrayHasKey('home',$result[0]);
		$this->assertArrayHasKey('url',$result[0]);
		$this->assertArrayHasKey('followers_count',$result[0]);
		$this->assertArrayHasKey('friends_count',$result[0]);
		$this->assertArrayHasKey('profile_image_url',$result[0]);
		$this->assertArrayHasKey('in_reply_to_status_id',$result[0]);
		$this->assertArrayHasKey('in_reply_to_user_id',$result[0]);
	}
	
	function testTwitterCanRetrieveFriendsTweets() {
		$result = $this->_twitter->friendsTweets();
		$this->assertNotNull($result);
	}
	
	function testTwitterCanRetrieveUserTimeLine() {
		$result = $this->_twitter->userTweets();
		$this->assertNotNull($result);
	}
}