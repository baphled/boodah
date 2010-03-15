<?php
require_once dirname(__FILE__) .'/../../libs/TestHelper.php';

class GitHubApiStub extends GitHubApi {
	
	function __construct($user) {
		parent::__construct($user);
	}
	function getClient() {
		return $this->_client;
	}
}
/**
 *  test case.
 */
class GitHubApiTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->_api = new GitHubApi('baphled');
		$this->_stub = new GitHubApiStub('baphled');
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		parent::tearDown ();
	}

	private function _commitsHelper($commit) {
		$this->assertNotEquals('',$commit->message);
		$this->assertEquals('Yomi Colledge',$commit->author->name);
		$this->assertEquals('baphled@boodah.net',$commit->author->email);
		$this->assertNotNull($commit->committed_date);
		$this->assertNotNull($commit->authored_date);
		$this->assertEquals('Yomi Colledge',$commit->committer->name);
		$this->assertEquals('baphled@boodah.net',$commit->committer->email);
	}
	
	function testConstructor() {
		$this->assertNotNull(new GitHubApi('baphled'));
	}

	function testApiHasClientProperty() {
		$this->assertClassHasAttribute('_client','GitHubApi');
		$this->assertClassHasAttribute('_responseObj','GitHubApi');
	}
	
	function testApiProjectInfoResultsReturnsAListOfProjectsAndADescription() {
		$result = $this->_api->getProjectOwner();
		$this->assertEquals('Yomi Colledge',$result);
	}
	
	function testApiCanRetrieveTheNumberOfRepositories() {
		$this->assertGreaterThanOrEqual(3,$this->_api->getRepositoryCount());
	}
	
	function testApiCanRetrieveAListOfProjectsWithinARepository() {
		$result = $this->_api->getProjects();
		$this->assertType('array',$result);
		foreach ($result as $repo) {
			$this->assertNotEquals('',$repo->name);
			$this->assertNotEquals('',$repo->description);
			$this->assertNotEquals('',$repo->owner);
			$this->assertNotEquals('',$repo->watchers);
			$this->assertNotEquals('',$repo->url);
			$this->assertType('string',$repo->homepage);
		}
	}
	
	function testApiCanRetrieveAListOfAllWatchedProjects() {
		$this->setExpectedException('Zend_Exception');
		$this->_api->getProjectCommits();
	}
	
	function testApiCanRetrievePrejectInfoReturnsNotNull() {
		$result = $this->_api->getProjectCommits('chatterl');
		$this->assertNotNull($result);
	}
	
	function testApiCanRetrieveProjectInfoReturnsStdObjects() {
		$result = $this->_api->getProjectCommits('chatterl');
		$this->assertType('stdClass',$result);
	}
	
	function testApiCanGetProjectCommitsAndTheRespectiveInformation() {
		$result = $this->_api->getProjectCommits('chatterl');
		foreach ($result->commits as $commit) {
			$this->assertNotEquals('',$commit->message);
			$this->assertEquals('Yomi Colledge',$commit->author->name);
			$this->assertEquals('baphled@boodah.net',$commit->author->email);
			$this->assertNotNull($commit->committed_date);
			$this->assertNotNull($commit->authored_date);
			$this->assertEquals('Yomi Colledge',$commit->committer->name);
			$this->assertEquals('baphled@boodah.net',$commit->committer->email);
		}
	}
	
	function testApiCanGetProjectsLastFiveCommits() {
		$result = $this->_api->getProjectCommits('chatterl');
		for($i=0;$i<5;$i++) {
			$commit = $result->commits[$i];
			$this->_commitsHelper($commit);
		}
	}
	
	function testApiCanGetTheLatestFiveCommits() {
		$result = $this->_api->getLatestCommits('chatterl');
		$this->assertType('array',$result);
		$this->assertEquals(5,count($result));
	}
	
	function testApiCanThrowsAnExceptionIfCantFindTheClientWeWantToRetrieveFollowersFor() {
		$this->setExpectedException('Zend_Exception');
		$api = new GitHubApi('blah');
		$api->getFollowers();
	}
	
	function testApiCanRetrieveAFollowersAsAStdObject() {
		$result = $this->_api->getFollowers();
		$this->assertType('stdClass',$result);
	}
	
	function testApiCanLoopThroughFollowers() {
		$result = $this->_api->getFollowers();
		foreach($result->users as $follower) {
			$this->assertType('string',$follower);
			$this->assertNotNull($follower);
		}
	}
	
	function testApiCanRetrieveAFollowingAsAStdObject() {
		$result = $this->_api->getFollowing();
		$this->assertType('stdClass',$result);
	}
	
	function testApiCanLoopThroughFollowing() {
		$result = $this->_api->getFollowing();
		foreach($result->users as $follower) {
			$this->assertType('string',$follower);
			$this->assertNotNull($follower);
		}
	}
}