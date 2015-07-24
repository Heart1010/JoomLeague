<?php
/**
 * Joomleague
 * 
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class
 */
class JoomleagueViewTreetonode extends JLGView
{
	public function display( $tpl = null )
	{
		if ( $this->getLayout() == 'form' )
		{
			$this->_displayForm( $tpl );
			return;
		}

		parent::display( $tpl );
	}

	function _displayForm( $tpl )
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$project_id = $app->getUserState('com_joomleagueproject');
		$uri 	= JFactory::getURI();
		$user 	= JFactory::getUser();
		$model	= $this->getModel();

		$lists = array();
		
		$node = $this->get('data');
		$match = $model->getNodeMatch();
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');
		$projectws = $this->get( 'Data', 'project' );
		
		$model = $this->getModel('project');
		$mdlTreetonodes = JModelLegacy::getInstance("Treetonodes", "JoomleagueModel");
		$team_id[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_TEAM'));
		if($projectteams = $mdlTreetonodes->getProjectTeamsOptions($model->_id))
		{
			$team_id=array_merge($team_id,$projectteams);
		}
		$lists['team']=$team_id;
		unset($team_id);

		$this->user = JFactory::getUser();
		$this->projectws = $projectws;
		$this->lists = $lists;
		// @todo fix!
		/* $this->division = $division; */
		/* $this->division_id = $division_id; */
		$this->node = $node;
		$this->match = $match;
		$this->pagination = $pagination;
		parent::display($tpl);
	}
}
