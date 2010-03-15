<?php
require_once dirname(__FILE__) .'/../../libs/TestHelper.php';

/**
 *  test case.
 */
class LightHouseApiTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->_api = new LightHouseApi('baphled');
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		parent::tearDown ();
		unset($this->_api);
	}
	
	function testConstructor() {
		$this->assertNotNull(new LightHouseApi('baphled'));
	}
	
	function testApiHasExpectedProperties() {
		$this->assertClassHasAttribute('_apiKey','LightHouseApi');
		$this->assertClassHasAttribute('_user','LightHouseApi');
	}

	function testApiCanRetrieveAClientsProjects() {
		$this->assertNotNull($this->_api->getProjects());
	}
	
	function testgetProjectsReturnsAnArray() {
		$this->assertType('array',$this->_api->getProjects());
	}
	
	function testGetProjectsResultsAreStdObjects() {
		$results = $this->_api->getProjects();
		
		foreach ($results as $result) {
			$this->assertNotEquals('',$result->project->id);
			$this->assertNotEquals('',$result->project->name);
			$this->assertNotEquals('',$result->project->created_at);
			$this->assertNotEquals('',$result->project->description);
			$this->assertNotEquals('',$result->project->permalink);
			$this->assertNotEquals('2',$result->project->license);
			$this->assertNotEquals('2',$result->project->public);
			$this->assertNotEquals('0',$result->project->open_tickets_count);
		}
	}

	function testApiCanRetrieveAProjectsTickets() {
		$result = $this->_api->getTickets('chatterl');
		$this->assertNotNull($result);
	}
	
	function testApiCanNotRetrieveTicketsForNonExistentProjects() {
		$this->assertFalse($this->_api->getTickets('blah'));
	}
	
	function testApiGetTicketsReturnsAListOfArraysAsResults() {
		$result = $this->_api->getTickets('chatterl');
		$this->assertType('array',$result);
	}
	
	function testApiGetTicketsReturnsTheTicketPropertiesWeExpect() {
		$results = $this->_api->getTickets('chatterl');
		foreach ($results as $result) {
			$ticket = $result->ticket;
			$this->assertNotNull($ticket->permalink);
			$this->assertNotNull($ticket->url);
			$this->assertNotNull($ticket->updated_at);
			$this->assertNotNull($ticket->project_id);
			$this->assertNotNull($ticket->title);
			$this->assertNotNull($ticket->number);
			$this->assertNotNull($ticket->creator_id);
			$this->assertNotEquals('blah',$ticket->tag);
			$this->assertNotNull($ticket->priority);
			$this->assertNotEquals('blah',$ticket->attachment_count);
			$this->assertNotNull($ticket->closed);
			$this->assertNotNull($ticket->creator_name);
			$this->assertNotNull($ticket->user_id);
			$this->assertNotEquals('blah',$ticket->assigned_user);
			$this->assertNotNull($ticket->state);
			$this->assertNotEquals('blah',$ticket->milestone_id);
			$this->assertNotNull($ticket->created_at);
		}
	}
	
	function testApiGetTicketCanReturnMoreThanOneResult() {
		$this->assertGreaterThan(1,count($this->_api->getTickets('chatterl')));
	}
	
	function testApiGetMileStonesIsNotNull() {
		$this->assertNotNull($this->_api->getMileStones('blah'));
	}
	
	function testApiGetMileStonesReturnsFalseIfResultsNotFound() {
		$this->assertFalse($this->_api->getMileStones('blah'));
	}
	
	function testApiGetMileStonesReturnsAListOfArraysAsResults() {
		$result = $this->_api->getMileStones('chatterl');
		$this->assertType('array',$result);
	}
	
	function testApiGetMuleStonesReturnTheExpectedProperties() {
		$results = $this->_api->getMileStones('chatterl');
		foreach ($results as $result) {
			$this->assertNotNull($result->milestone->permalink);
			$this->assertNotNull($result->milestone->url);
			$this->assertNotNull($result->milestone->updated_at);
			$this->assertNotNull($result->milestone->tickets_count);
			$this->assertNotNull($result->milestone->project_id);
			$this->assertNotNull($result->milestone->title);
			$this->assertNotNull($result->milestone->goals);
			$this->assertNotNull($result->milestone->id);
			$this->assertNotNull($result->milestone->open_tickets_count);
			$this->assertNotNull($result->milestone->due_on);
			$this->assertNotNull($result->milestone->created_at);
		}
	}
	
	function testApiGetProjectReturnsFalseIfNotFound() {
		$this->assertFalse($this->_api->getProject('blah'));
	}
	
	function testApiGetProjectReturnsStdClass() {
		$result = $this->_api->getProject('chatterl');
		$this->assertType('stdClass',$result);
	}
	
	function testApiGetTicketReturnsFalseIfNotFound() {
		$this->assertFalse($this->_api->getTicket('blah',1));
	}
	
	function testApiGetTicketReturnsFalseIfNumberNotFound() {
		$this->setExpectedException('Zend_Exception');
		$this->_api->getTicket('chatterl',-1);
		
	}
	
	function testApiGetTicketReturnsStdClass() {
		$result = $this->_api->getTicket('chatterl',2);
		$this->assertType('stdClass',$result);
	}
	
	function testApiGetTicketReturnsPropertiesAreWhatWeExpect() {
		$result = $this->_api->getTicket('chatterl',2);
		$this->assertNotNull($result->permalink);
		$this->assertNotNull($result->updated_at);
		$this->assertNotNull($result->project_id);
		$this->assertNotNull($result->body_html);
		$this->assertNotNull($result->title);
		$this->assertNotNull($result->number);
		$this->assertNotNull($result->body);
		$this->assertType('array',$result->versions);
		foreach ($result->versions as $version) {
			$this->assertNotNull($version->permalink);
			$this->assertNotNull($result->url);
			$this->assertNotNull($result->updated_at);
			$this->assertNotNull($result->project_id);
			$this->assertNotEquals('blah',$result->body_html);
			$this->assertNotNull($result->title);
			$this->assertNotNull($result->number);
			$this->assertNotEquals('blah',$result->body);
			$this->assertNotNull($result->creator_id);
			$this->assertNotEquals('blah',$result->tag);
			$this->assertNotNull($result->attachments_count);
			$this->assertNotNull($result->creator_name);
			//$this->assertType('stdClass',$result->diffable_attributes);
			$this->assertNotEquals('blah',$result->closed);
			$this->assertNotEquals('blah',$result->assigned_user_name);
			$this->assertNotNull($result->user_name);
			$this->assertNotNull($result->user_id);
			$this->assertNotEquals('blah',$result->assigned_user_id);
			$this->assertNotNull($result->state);
			$this->assertNotEquals('blah',$result->milestone_id);
			$this->assertNotNull($result->created_at);
		}
	}
}