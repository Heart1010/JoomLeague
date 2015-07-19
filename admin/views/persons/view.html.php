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
class JoomleagueViewPersons extends JLGView
{

	public function display($tpl=null)
	{
		if ($this->getLayout() == 'assignplayers')
		{
			$this->_displayAssignPlayers($tpl);
			return;
		}
		JHtml::_('behavior.calendar');
		$option = $this->input->getCmd('option');
		$params	= JComponentHelper::getParams( $option );
		$app = JFactory::getApplication();
		
		$model	= $this->getModel();

		$filter_state		= $app->getUserStateFromRequest($option.'pl_filter_state', 'filter_state', '', 'word');
		$filter_order		= $app->getUserStateFromRequest($option.'pl_filter_order', 'filter_order', 'pl.ordering', 'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'pl_filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search				= $app->getUserStateFromRequest($option.'pl_search', 'search', '', 'string');
		$search_mode		= $app->getUserStateFromRequest($option.'pl_search_mode', 'search_mode', '', 'string');

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		$app->setUserState($option.'task','');

		// state filter
		$lists['state']=JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		// search filter
		$lists['search']=$search;
		$lists['search_mode']=$search_mode;

		//build the html select list for positions
		$positionsList[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_POSITION'));
		$positions=JModelLegacy::getInstance('person','joomleaguemodel')->getPositions();
		if ($positions){ $positions=array_merge($positionsList,$positions);}
		$lists['positions']=$positions;
		unset($positionsList);

		//build the html options for nation
		$nation[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_NATION'));
		if ($res = Countries::getCountryOptions()){$nation=array_merge($nation,$res);}
		$lists['nation']=$nation;
		unset($nation);

		$this->user = JFactory::getUser();
		$this->config = JFactory::getConfig();
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->request_url = JFactory::getURI()->toString();
		$this->component_params = $params;
		
		
		$sideMenu = JoomleagueHelper::sideMenu();
		
		$this->sidebar = $sideMenu;
		
		$this->addToolbar();
		parent::display($tpl);
	}

	function _displayAssignPlayers($tpl=null)
	{
		$option 		= $this->input->getCmd('option');
		$params			= JComponentHelper::getParams( $option );
		$app 		= JFactory::getApplication();
		$model 			= $this->getModel();
		$project_id 	= $app->getUserState($option.'project');
		$mdlProject 	= JModelLegacy::getInstance("project", "JoomLeagueModel");
		$project_name 	= $mdlProject->getProjectName($project_id);
		$project_team_id = $app->getUserState($option.'project_team_id');
		$team_name = $model->getProjectTeamName($project_team_id);
		$mdlQuickAdd = JLGModel::getInstance('Quickadd','JoomleagueModel');

		$filter_state		= $app->getUserStateFromRequest($option.'pl_filter_state', 'filter_state', '', 'word');
		$filter_order		= $app->getUserStateFromRequest($option.'pl_filter_order', 'filter_order', 'pl.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'pl_filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search				= $app->getUserStateFromRequest($option.'pl_search', 'search', '', 'string');
		$search_mode		= $app->getUserStateFromRequest($option.'pl_search_mode',	'search_mode', '', 'string');

		//save icon should be replaced by the apply
		JLToolBarHelper::apply('person.saveassigned','COM_JOOMLEAGUE_ADMIN_PERSONS_SAVE_SELECTED');		
		
		// Set toolbar items for the page
		$type=JRequest::getInt('type');
		if ($type==0)
		{
                    //back icon should be replaced by the abort/close icon
                    JToolBarHelper::back('COM_JOOMLEAGUE_ADMIN_PERSONS_BACK','index.php?option=com_joomleague&task=teamplayer.display&view=teamplayers');
                    JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_ASSIGN_PLAYERS'),'generic.png');
                    $items = $mdlQuickAdd->getNotAssignedPlayers(JString::strtolower($search),$project_team_id);
		}
		elseif ($type==1)
		{
                    //back icon should be replaced by the abort/close icon
                    JToolBarHelper::back('COM_JOOMLEAGUE_ADMIN_PERSONS_BACK','index.php?option=com_joomleague&task=teamstaff.display&view=teamstaffs');
                    JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_ASSIGN_STAFF'),'generic.png');
                    $items = $mdlQuickAdd->getNotAssignedStaff(JString::strtolower($search),$project_team_id);
		}
		elseif ($type==2)
		{
                    //back icon should be replaced by the abort/close icon
                    JToolBarHelper::back('COM_JOOMLEAGUE_ADMIN_PERSONS_BACK','index.php?option=com_joomleague&view=projectreferees&task=projectreferee.display');
                    JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_ASSIGN_REFEREES'),'generic.png');
                    $items = $mdlQuickAdd->getNotAssignedReferees(JString::strtolower($search),$project_id);
		}

		JToolBarHelper::help('screen.joomleague',true);		
		
		$limit = $app->getUserStateFromRequest('global.list.limit','limit',$app->getCfg('list_limit'),'int');

		jimport('joomla.html.pagination');
		$pagination = new JPagination($mdlQuickAdd->_total,$this->input->getÍnt('limitstart',0),$limit);
		$mdlQuickAdd->_pagination=$pagination;

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		// search filter
		$lists['search']=$search;
		$lists['search_mode']=$search_mode;

		$this->prjid = $project_id;
		$this->prj_name = $project_name;
		/* $this->team_id = $team_id; */
		$this->team_name = $team_name;
		$this->project_team_id = $project_team_id;
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->request_url = JFactory::getURI()->toString();
		$this->type = $type;
		$this->component_params = $params;
				 
		parent::display($tpl);
	}

	/**
	 * Displays a calendar control field with optional onupdate js handler
	 *
	 * @param	string	The date value
	 * @param	string	The name of the text field
	 * @param	string	The id of the text field
	 * @param	string	The date format
	 * @param	string	js function to call on date update
	 * @param	array	Additional html attributes
	 */
	function calendar($value,$name,$id,$format='%Y-%m-%d',$attribs=null,$onUpdate=null,$i=null)
	{
		if (is_array($attribs)){$attribs=JArrayHelper::toString($attribs);}
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('window.addEvent(\'domready\',function() {Calendar.setup({
	        inputField     :    "'.$id.'",    // id of the input field
	        ifFormat       :    "'.$format.'",     // format of the input field
	        button         :    "'.$id.'_img", // trigger for the calendar (button ID)
	        align          :    "Tl",          // alignment (defaults to "Bl")
	        onUpdate       :    '.($onUpdate ? $onUpdate : 'null').',
	        singleClick    :    true
    	});});');
		$html='';
		$html .= '<input onchange="document.getElementById(\'cb'.$i.'\').checked=true" type="text" name="'.$name.'" id="'.$id.'" value="'.htmlspecialchars($value,ENT_COMPAT,'UTF-8').'" '.$attribs.' />'.
				 '<img class="calendar" style="margin-top: 5px" src="'.JUri::root(true).'/templates/system/images/calendar.png" alt="calendar" id="'.$id.'_img" />';
		return $html;
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PERSONS_TITLE'),'user');

		JLToolBarHelper::publishList('person.publish');
		JLToolBarHelper::unpublishList('person.unpublish');
		JToolBarHelper::divider();
		JLToolBarHelper::apply('person.saveshort');
		JLToolBarHelper::editList('person.edit');
		JLToolBarHelper::addNew('person.add');
		JLToolBarHelper::custom('person.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('person.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JLToolBarHelper::deleteList('COM_JOOMLEAGUE_ADMIN_PERSONS_DELETE_WARNING','person.remove');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}
}
