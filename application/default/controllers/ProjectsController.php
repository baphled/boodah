<?php

/**
 * ProjectsController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class ProjectsController extends BaseController {
	
	function init() {
		parent::init();
		
		$this->view->headTitle('Baphled Projects');
		$this->view->title = 'Projects';
		unset($this->view->rightPanel['tweets']);
		$milestones = array();
		$menu = array();
		
		foreach ($this->view->projects as $project) {
			$menu['link'] = "/projects/info/{$project->name}";
			$menu['title'] = $project->description;
			$menu['text'] = $project->name;
			$subMenu[] = $menu;
		}
		$projects = $this->_handleCacheResult('projectIssues','_lighthouse','getProjects');
		foreach ($projects as $project) {
			$projectInfo = $this->_handleCacheResult("milestones_{$project->project->permalink}",'_lighthouse','getMileStones',$project->project->permalink);
			$milestones[$project->project->permalink] = $projectInfo;
		}
		$this->view->subMenu = $subMenu;
		$this->view->rightPanel['milestones'] = $this->view->partial('common/_right_panel.phtml',array('name'=>'issues','issues'=>$projects,'milestones'=>$milestones));
	}
	
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$projects = $this->_handleCacheResult('personalProjects','_github','getProjects');
		foreach ($projects as $project) {
			$projectInfo = $this->_handleCacheResult("projectInfo_{$project->name}",'_github','getLatestCommits',$project->name);
			$projects[$project->name] = $projectInfo;
		}
		$this->view->projectInfo = $projects;
	}
	
	function infoAction() {
		$projectName = $this->_request->getParam('project');
		$project = $this->_handleCacheResult("project_{$projectName}",'_github','getProject',$projectName);
		$projectInfo = $this->_handleCacheResult("projectInfo_{$project->repository->name}",'_github','getLatestCommits',$project->repository->name);
		
		$this->view->project = $project;
		$this->view->projectInfo = $projectInfo;
	}
}