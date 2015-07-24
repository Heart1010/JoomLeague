<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


/**
 * HTML View class
 */
class JoomleagueViewProjectteams extends JLGView
{
	public function display($tpl=null)
	{
		if ($this->getLayout() == 'editlist')
		{
			$this->_displayEditlist($tpl);
			return;
		}

		if ($this->getLayout() == 'changeteams')
		{
			$this->_displayChangeTeams($tpl);
			return;
		}
		
		if ($this->getLayout() == 'default')
		{
			$this->_displayDefault($tpl);
			return;
		}
		
		if ($this->getLayout() == 'copy')
		{
			$this->_displayCopy($tpl);
			return;
		}

		parent::display($tpl);
	}

  function _displayChangeTeams($tpl)
	{
		$option = JRequest::getCmd('option');
		$app 	= JFactory::getApplication();
		$project_id = $app->getUserState( $option . 'project' );
		
		$projectteams = $this->get('Data');
		$model = $this->getModel();
		
		//build the html select list for all teams
		$allTeams = array();
		$all_teams[] = JHtml::_( 'select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_SELECT_TEAM' ) );
		if( $allTeams = $model->getAllTeams($project_id) ) 
    {
			$all_teams=array_merge($all_teams,$allTeams);
		}
		$lists['all_teams']=$all_teams;
		unset($all_teams);
		
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_CHANGEASSIGN_TEAMS'),'install');
			
		JLToolBarHelper::custom('projectteam.storechangeteams','move.png','move_f2.png','COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_BUTTON_STORE_CHANGE_TEAMS',false);
		JToolBarHelper::back();	
		
		$this->projectteams = $projectteams;
		$this->lists = $lists;
		
		parent::display($tpl);
	}
  
  	
	function _displayEditlist($tpl)
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$project_id = $app->getUserState( $option . 'project' );
		
		$db = JFactory::getDbo();
		$uri = JFactory::getURI();

		$filter_state = $app->getUserStateFromRequest($option.'tl_filter_state', 'filter_state', '', 'word');
		$filter_order = $app->getUserStateFromRequest($option.'tl_filter_order', 'filter_order', 't.name', 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest($option.'tl_filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search	= $app->getUserStateFromRequest($option.'tl_search', 'search', '', 'string');
		$search_mode = $app->getUserStateFromRequest($option.'tl_search_mode', 'search_mode', '', 'string');
		$search	= JString::strtolower($search);

		$projectteam = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');
		$model = $this->getModel();

		// state filter
		$lists['state'] = JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;
		$lists['search_mode'] = $search_mode;
		$projectws = $this->get('Data','project');

		//build the html select list for project assigned teams
		$ress = array();
		$res1 = array();
		$notusedteams = array();

		if ($ress = $model->getProjectTeams($project_id))
		{
			$teamslist=array();
			foreach($ress as $res)
			{
				if(empty($res1->info))
				{
					$project_teamslist[] = JHtmlSelect::option($res->value,$res->text);
				}
				else
				{
					$project_teamslist[] = JHtmlSelect::option($res->value,$res->text.' ('.$res->info.')');
				}
			}

			$lists['project_teams'] = JHtmlSelect::genericlist($project_teamslist, 'project_teamslist[]',
																' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30,count($ress)).'"',
																'value',
																'text');
		}
		else
		{
			$lists['project_teams']= '<select name="project_teamslist[]" id="project_teamslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		if ($ress1 = $model->getTeams())
		{
			if ($ress = $model->getProjectTeams($project_id))
			{
				foreach ($ress1 as $res1)
				{
					$used=0;
					foreach ($ress as $res)
					{
						if ($res1->value == $res->value){$used=1;}
					}

					if ($used == 0 && !empty($res1->info)){
						$notusedteams[]=JHtmlSelect::option($res1->value,$res1->text.' ('.$res1->info.')');
					}
					elseif($used == 0 && empty($res1->info))
					{
						$notusedteams[] = JHtmlSelect::option($res1->value,$res1->text);
					}
				}
			}
			else
			{
				foreach ($ress1 as $res1)
				{
					if(empty($res1->info))
					{
						$notusedteams[] = JHtmlSelect::option($res1->value,$res1->text);
					}
					else
					{
						$notusedteams[] = JHtmlSelect::option($res1->value,$res1->text.' ('.$res1->info.')');
					}
				}
			}
		}
		else
		{
			JError::raiseWarning('ERROR_CODE','<br />'.JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_ADD_TEAM').'<br /><br />');
		}

		//build the html select list for teams
		if (count($notusedteams) > 0)
		{
			$lists['teams'] = JHtmlSelect::genericlist( $notusedteams,
														'teamslist[]',
														' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30,count($notusedteams)).'"',
														'value',
														'text');
		}
		else
		{
			$lists['teams'] = '<select name="teamslist[]" id="teamslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		unset($res);
		unset($res1);
		unset($notusedteams);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->projectteam = $projectteam;
		$this->projectws = $projectws;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		$this->addToolbar_Editlist();		
		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$document = JFactory::getDocument();
		$option = JRequest::getCmd('option');
		$app 	= JFactory::getApplication();
		$project_id = $app->getUserState( $option . 'project' );
		
		$db = JFactory::getDbo();
		$uri = JFactory::getURI();

		$baseurl    = JUri::root();
		/*
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Autocompleter.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Autocompleter.Request.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Observer.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/quickaddteam.js');
		$document->addStyleSheet($baseurl.'administrator/components/com_joomleague/assets/css/Autocompleter.css');
		*/		
		$document->addStyleSheet($baseurl.'administrator/components/com_joomleague/assets/css/Autocompleter.css');

		$filter_state		= $app->getUserStateFromRequest($option.'tl_filter_state',		'filter_state',		'',			'word');
		$filter_order		= $app->getUserStateFromRequest($option.'tl_filter_order',		'filter_order',		't.name',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'tl_filter_order_Dir',	'filter_order_Dir',	'',			'word');
		$search				= $app->getUserStateFromRequest($option.'tl_search',				'search',			'',			'string');
		$division			= $app->getUserStateFromRequest($option.'tl_division',			'division',			'',			'string');
		
		$search_mode		= $app->getUserStateFromRequest($option.'tl_search_mode',			'search_mode',		'',			'string');
		$search				= JString::strtolower($search);

		$projectteam	= $this->get('Data');
		$total			= $this->get('Total');
		$pagination 	= $this->get('Pagination');
		$model			= $this->getModel();

		// state filter
		$lists['state']=JHtml::_('grid.state',$filter_state);

		// search filter
		$lists['search']=$search;
		$lists['search_mode']=$search_mode;

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		//build the html options for divisions
		$divisions[]=JHtmlSelect::option('0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_DIVISION'));
		$mdlDivisions = JModelLegacy::getInstance("divisions", "JoomLeagueModel");
		if ($res = $mdlDivisions->getDivisions($project_id)){
			$divisions=array_merge($divisions,$res);
		}
		$lists['divisions']=$divisions;
		unset($divisions);

		$projectws	= $this->get('Data','project');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->projectteam = $projectteam;
		$this->division = $division;
		$this->projectws = $projectws;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		$this->addToolbar();			
		parent::display($tpl);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $tpl
	 */
	function _displayCopy($tpl)
	{
		$document = JFactory::getDocument();
		$option = JRequest::getCmd('option');
		$app 	= JFactory::getApplication();
		$project_id = $app->getUserState( $option . 'project' );
	
		$uri = JFactory::getURI();
			
		$ptids = JRequest::getVar('cid', array(), 'post', 'array');
	
		$model = $this->getModel();
	
		$lists = array();
					
		//build the html select list for all teams
		$options = JoomleagueHelper::getProjects();
		
		$lists['projects'] = JHtml::_('select.genericlist', $options, 'dest', '', 'id', 'name');
		
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_COPY_DEST'),'Teams');
		
		JLToolBarHelper::apply('projectteam.copy');		
		JToolBarHelper::back();
		
		$this->ptids = $ptids;
		$this->lists = $lists;
		$this->request_url = $uri->toString();
	
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{ 	
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_TITLE'));

		JLToolBarHelper::apply('projectteam.saveshort');
		JLToolBarHelper::custom('projectteam.changeteams','move.png','move_f2.png','COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_BUTTON_CHANGE_TEAMS',false);
		JLToolBarHelper::custom('projectteam.editlist','upload.png','upload_f2.png','COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_BUTTON_ASSIGN',false);
		JLToolBarHelper::custom('projectteam.copy','copy','copy', 'COM_JOOMLEAGUE_GLOBAL_COPY', true);
		JToolBarHelper::divider();

		JToolBarHelper::help('screen.joomleague',true);	
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar_Editlist()
	{ 		
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'COM_JOOMLEAGUE_ADMIN_PROJECTTEAMS_ASSIGN' ) );
		JLToolBarHelper::save( 'projectteam.save_teamslist' );
		
		// for existing items the button is renamed `close` and the apply button is showed
		JLToolBarHelper::cancel( 'projectteam.cancel', 'COM_JOOMLEAGUE_GLOBAL_CLOSE' );
		
		JToolBarHelper::help( 'screen.joomleague', true );	
	}
}
