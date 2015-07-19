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
class JoomleagueViewRounds extends JLGView
{

	public function display($tpl=null)
	{
		if ($this->getLayout()=='default')
		{
			$this->_displayDefault($tpl);
			return;
		}
		else if ($this->getLayout()=='populate')
		{
			$this->_displayPopulate($tpl);
			return;
		}
		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$option 			= JRequest::getCmd('option');
		$uri 				= JFactory::getURI();
		$url 				= $uri->toString();
		$matchday 			= $this->get('Data');
		$total 				= $this->get('Total');
		$pagination 		= $this->get('Pagination');
		$model 				= $this->getModel();
		$projectws 			= $this->get('Data','project');
		$teams 				= $this->get('projectteams');
		$state 				= $this->get('state');
		$filter_order		= $state->get('filter_order');
		$filter_order_Dir 	= $state->get('filter_order_Dir');

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order']	    = $filter_order;
		$massadd			= $this->input->get('massadd');

				//build the html options for divisions
		$divisions[]=JHtmlSelect::option('0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_DIVISION'));
		$mdlDivisions = JModelLegacy::getInstance("divisions", "JoomLeagueModel");
		if ($res = $mdlDivisions->getDivisions($projectws->id)){
			$divisions=array_merge($divisions,$res);
		}
		$lists['divisions']=$divisions;

		$this->massadd = $massadd;
		$this->countProjectTeams = count($teams);
		$this->lists = $lists;
		$this->matchday = $matchday;
		$this->projectws = $projectws;
		$this->pagination = $pagination;
		$this->request_url = $url;
		
		$populate = 0;
		$this->populate = $populate;
		
		$this->addToolbar();
		parent::display($tpl);
	}

	function _displayPopulate($tpl)
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		$document	= JFactory::getDocument();
		$uri		= JFactory::getURI();
		$url 		= $uri->toString();
		$model 		= $this->getModel();
		$projectws	= $this->get('Data','project');

		$document->setTitle(JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_POPULATE_TITLE'));

		$lists = array();
		$iScheduleType = 0;
		$options = array();
		$options[] = JHtml::_('select.option', $iScheduleType++, JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_POPULATE_TYPE_SINGLE_ROUND_ROBIN'));
		$options[] = JHtml::_('select.option', $iScheduleType++, JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_POPULATE_TYPE_DOUBLE_ROUND_ROBIN'));
		$path = JPath::clean(JPATH_ROOT.'/images/com_joomleague/database/round_populate_templates');
		$files = JFolder::files($path,'.',false);
		foreach ($files as $file) {
			$filename = strtoupper(JFile::stripExt($file));
			$options[] = JHtml::_('select.option', $file, JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_POPULATE_TYPE_'.$filename));
		}
		$lists['scheduling'] = JHtml::_('select.genericlist', $options, 'scheduling', 'onchange="handleOnChange_scheduling(this)"', 'value', 'text');

		$teams = $this->get('projectteams');
		$options = array();
		foreach ($teams as $t) {
			$options[] = JHtml::_('select.option', $t->projectteam_id, $t->text);
		}
		$lists['teamsorder'] = JHtml::_('select.genericlist', $options, 'teamsorder[]', 'multiple="multiple" size="20"');

		$this->projectws = $projectws;
		$this->request_url = $url;
		$this->lists = $lists;
		
		$this->addToolbar_Populate();
		parent::display($tpl);
	}

	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_TITLE'),'Matchdays');

		if (!$this->massadd)
		{
			JLToolBarHelper::apply('round.saveshort');
			JToolBarHelper::divider();
			JLToolBarHelper::custom('round.massadd','new.png','new_f2.png','COM_JOOMLEAGUE_ADMIN_ROUNDS_MASSADD_BUTTON',false);
			$teams = $this->get('projectteams');
			if($teams && count($teams) >0) {
				JLToolBarHelper::addNew('round.populate', 'COM_JOOMLEAGUE_ADMIN_ROUNDS_POPULATE_BUTTON', false);
			}
			JLToolBarHelper::addNew('round.save');
			JToolBarHelper::divider();
			JLToolBarHelper::deleteList(JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_DELETE_WARNING'),'round.deletematches','COM_JOOMLEAGUE_ADMIN_ROUNDS_MASSDEL_BUTTON');
			JLToolBarHelper::deleteList(JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_DELETE_WARNING'),'round.remove');
			JToolBarHelper::divider();
		}
		else
		{
			JLToolBarHelper::custom('round.cancelmassadd','cancel.png','cancel_f2.png','COM_JOOMLEAGUE_ADMIN_ROUNDS_MASSADD_CANCEL',false);
		}
		JToolBarHelper::help('screen.joomleague',true);
	}

	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar_Populate()
	{
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_ROUNDS_POPULATE_TITLE'));
		JLToolBarHelper::apply('round.startpopulate');
		JToolBarHelper::back();
	}
}
