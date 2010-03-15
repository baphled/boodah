<?php
/**
 * Used to intereact with GitHub's API
 * 
 * @author Yomi (baphled) Colledge
 * @version $Id: GitHubApi.php 34 2009-04-26 17:43:31Z baphled $
 *
 */
class GitHubApi extends Baphled_Http_Api {
	
	protected $_client;
	
	protected $_responseObj;
	
	function __construct($user) {
		parent::__construct($user);
		$this->_response = null;
	}
	
	/**
	 * Gets a clients information from github
	 * 
	 * @return Zend_Json $jsonResponse	Clients GitHub information
	 * 
	 */
	protected function _getProjectInfo() {
		return $this->_getJsonResponse('http://github.com/api/v1/json/' .$this->_user);
	}

	function getProject($project) {
		return $this->_getJsonResponse("http://github.com/api/v2/json/repos/show/{$this->_user}/{$project}");
	}
	/**
	 * Retrieves a projects commits
	 * 
	 * @return  Zend_Json $jsonResponse	A projects commits
	 * 
	 */
	protected function _retrieveProjectCommits($project) {
		$this->_validateProjectName($project);
		return $this->_getJsonResponse("http://github.com/api/v2/json/commits/list/{$this->_user}/{$project}/master");
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
	
	
	function getProjectOwner() {
		$result = $this->_getProjectInfo();
		return $result->user->name;
	}
	
	function getRepositoryCount() {
		return count($this->_getProjectInfo()->user->repositories);
	}

	function getFollowers() {
		return $this->_getJsonResponse("http://github.com/api/v2/json/user/show/{$this->_user}/followers");
	}
	
	function getFollowing() {
		return $this->_getJsonResponse("http://github.com/api/v2/json/user/show/{$this->_user}/following");
	}
	
	function getProjects() {
		return $this->_getProjectInfo()->user->repositories;
	}
	
	function getProjectCommits($project=null) {
		$result = $this->_retrieveProjectCommits($project);
		return $result;
	}
	
	function getLatestCommits($project=null,$limit=5) {
		$this->_validateProjectName($project);
		$result = $this->getProjectCommits($project);
		$count = count($result->commits);
		$commitCount = $count > $limit ? $limit : $count; 
		$commits = null;
		for($i=0;$i<$commitCount;$i++) {
			$commits[] = $result->commits[$i];
		}
		return $commits;
	}
}