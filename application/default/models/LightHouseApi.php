<?php
/**
 * API wrapper for calling LightHouse API
 * 
 * @author Yomi (baphled) Colledge
 * @version $Id$
 *
 */
class LightHouseApi extends Baphled_Http_Api {
	
	protected $_apiKey;
	
	protected $_user;
	
	function __construct($user=null) {
		parent::__construct($user);
		$this->_apiKey = $this->_config->lighthouse->api;
	}
	
	function getProjects() {
		return $this->_getJsonResponse("http://{$this->_user}.lighthouseapp.com/projects.json?_token={$this->_apiKey}")->projects;
	}
	
	function getTickets($project) {
		$result = $this->_getProjectId($project);
		if (false != $result) {
			return $this->_getJsonResponse("http://{$this->_user}.lighthouseapp.com/projects/{$result}/tickets.json?_token={$this->_apiKey}")->tickets;
			
		}
		return false;
	}
	
	function getTicket($project,$ticket_id) {
		$result = $this->_getProjectId($project);
		if (false != $result) {
			return $this->_getJsonResponse("http://{$this->_user}.lighthouseapp.com/projects/{$result}/tickets/{$ticket_id}.json?_token={$this->_apiKey}")->ticket;
			
		}
		return false;
	}
	
	function getMileStones($project) {
		$result = $this->_getProjectId($project);
		if (false != $result) {
			$response = $this->_getJsonResponse("http://{$this->_user}.lighthouseapp.com/projects/{$result}/milestones.json?_token={$this->_apiKey}");
			if(property_exists($response,'milestones')) {
				return $response->milestones;
			}
		}
		return false;
	}
	
	function getProject($project) {
		$result = $this->_getProjectId($project);
		if(false != $result) {
			return $this->_getJsonResponse("http://{$this->_user}.lighthouseapp.com/projects/{$result}.json?_token={$this->_apiKey}")->project;
		}
		return false;
	}
	
	protected function _getProjectId($project) {
		$results = $this->getProjects();
		foreach ($results as $result) {
			if($result->project->permalink == $project) {
				return $result->project->id;
			}
		}
		return false;
	}
}