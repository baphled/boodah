<?php

/**
 * TrackingController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class TrackingController extends BaseController  {
	
	function init() {
		parent::init();
		$this->_setNeedsParams(array('milestone','tickets'));
		$this->view->title = 'Bug Tracking';
		unset($this->view->rightPanel['tweets']);
		$milestones = array();
		$projects = $this->_handleCacheResult('projectIssues','_lighthouse','getProjects');
		foreach ($projects as $project) {
			$name = str_replace('-','_',$project->project->permalink);
			$projectInfo = $this->_handleCacheResult("milestones_{$name}",'_lighthouse','getMileStones',$project->project->permalink);
			$milestones[$project->project->permalink] = $projectInfo;
		}
		
		$this->view->rightPanel['milestones'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'issues','issues'=>$projects,'milestones'=>$milestones));
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$milestones = array();
		$projects = $this->_handleCacheResult('projectIssues','_lighthouse','getProjects');
		foreach ($projects as $project) {
			$name = str_replace('-','_',$project->project->permalink);
			$projectInfo = $this->_handleCacheResult("milestones_{$name}",'_lighthouse','getMileStones',$project->project->permalink);
			$milestones[$project->project->permalink] = $projectInfo;
			$result[] = $this->view->partial('_project_info.phtml',array('project'=>$project->project,'milestones'=>$milestones));
		}
		$this->view->milestones = $result;
	}

	function milestoneAction() {
		$milestones = array();
		$milestone = $this->_request->getParam('milestone');
		$project = $this->_handleCacheResult("project_{$milestone}",'_lighthouse','getProject',$milestone);
		
		$projectInfo = $this->_handleCacheResult("milestones_{$project->permalink}",'_lighthouse','getMileStones',$project->permalink);
		$milestones[$project->permalink] = $projectInfo;
		
		$tickets[$project->permalink] = $this->_handleCacheResult("tickets_{$project->permalink}",'_lighthouse','getTickets', $project->permalink);
		$this->view->milestones = $this->view->partial('_project_info.phtml',array('project'=>$project,'milestones'=>$milestones,'tickets'=>$tickets));
		$this->render('index');
	}
	
	function ticketsAction() {
		$milestone = $this->_request->getParam('milestone');
		$this->view->ticketLog = $this->_lighthouse->getTickets($milestone);
		$this->view->projectName = $milestone;
	}
	
	function ticketAction() {
		$ticket = $this->_request->getParam('ticket');
		$milestone = $this->_request->getParam('milestone');
		$this->view->ticketLog = $this->_handleCacheResult("_tickets_{$milestone}",'_lighthouse',getTicket, array($milestone,$ticket));
		$this->view->ticketLog = $this->_lighthouse->getTicket($milestone,$ticket);
		$this->view->projectName = $milestone;
	}
}