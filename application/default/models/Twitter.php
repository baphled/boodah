<?php
/**
 * Basic Twitter API Wrapper
 * 
 * @author Yomi (baphled) Colledge
 * @version 0.1
 *
 */
class Twitter {
	
	public $_twitter;
	
	function __construct() {
		try {
			Zend_SetupInit::setupInit();
			$this->_config = Zend_Registry::get(Zend_SetupInit::$_env);
			$user = $this->_config->twitter->user;
			$pass = $this->_config->twitter->pass;
			$this->_twitter = new Zend_Service_Twitter($user,$pass);
			$this->_response = $this->_twitter->account->verifyCredentials();
			if($this->_response->error()) {
				throw new Zend_Exception('An error occurred.');
			}
		} catch (Exception $e) {
			// @todo please catch me.
		}
	}
	
	function latestTweet() {
		$result = array();
		$result['msg'] = $this->_response->status->text;
		$result['sent'] = $this->_response->status->created_at;
		return $result['sent'] ." : " .$result['msg'];
	}
	
	function followers() {
		return $this->_response->followers_count();
	}
	
	function friends() {
		return $this->_response->friends_count();
	}
	
	function favourites() {
		$results = array();
		foreach ($this->_twitter->favorite->favorites() as $favourite) {
			$results[] = $this->_getTwitterResponse($favourite);
		}
		return $results;
	}
	
	function friendsTweets() {
		$results = array();
		foreach ($this->_twitter->status->friendsTimeline() as $tweet) {
			$results[] = $this->_getTwitterResponse($tweet);
		}
		return $results;
	}
	
	function userTweets() {
		$results = array();
		foreach ($this->_twitter->status->userTimeline() as $tweet) {
			$results[] = $this->_getTwitterResponse($tweet);
		}
		return $results;
	}
	
	protected function _getTwitterResponse($tweet) {
		$result = array();
		$result['nick'] = (string)$tweet->user->screen_name;
		$result['id'] = (string)$tweet->id;
		$result['msg'] = (string)$tweet->text;
		$result['name'] = (string)$tweet->user->name;
		$result['created_at'] = (string)$tweet->created_at;
		$result['sent'] = (string)$tweet->created_at;
		$result['location'] = (string)$tweet->user->location;
		$result['description'] = (string)$tweet->user->description;
		$result['home'] = "http://twitter.com/{$result['nick']}";
		$result['url'] = (string)$tweet->user->url;
		$result['followers_count'] = (string)$tweet->user->followers_count;
		$result['friends_count'] = (string)$tweet->user->friends_count;
		$result['profile_image_url'] = (string)$tweet->user->profile_image_url;
		$result['in_reply_to_status_id'] = (string)$tweet->in_reply_to_status_id;
		$result['in_reply_to_user_id'] = (string)$tweet->in_reply_to_user_id;
		return $result;
	}
}