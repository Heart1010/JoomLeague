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
class JoomleagueViewTreetos extends JLGView
{

	public function display($tpl=null)
	{
		$app 	= JFactory::getApplication();
		$jinput = $app->input;
		
		$option		= $jinput->getCmd('option');
		$project_id	= $app->getUserState( $option . 'project' );
		$uri 		= JFactory::getURI()->toString();
		$user		= JFactory::getUser();
		
		// Get data from the model
		$items		= $this->get('Data');
		$total		= $this->get('Total');
		$pagination = $this->get('Pagination');
		
		$model = $this->getModel();
		$projectws = $this->get('Data','project');
		$division = $app->getUserStateFromRequest($option.'tt_division','division','','string');

		//build the html options for divisions
		$divisions[]=JHtmlSelect::option('0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_DIVISION'));
		$mdlDivisions = JModelLegacy::getInstance("divisions", "JoomLeagueModel");
		if ($res = $mdlDivisions->getDivisions($project_id)){
			$divisions=array_merge($divisions,$res);
		}
		$lists['divisions']=$divisions;
		unset($divisions);
	
		$this->user = $user;
		$this->lists = $lists;
		$this->items = $items;
		$this->projectws = $projectws;
		$this->division = $division;
		$this->total = $total;
		$this->pagination = $pagination;
		$this->request_url = $uri;

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_TITLE'),'jl-Tree');

		JLToolBarHelper::apply('treeto.saveshort');
		JLToolBarHelper::publishList('treeto.publish');
		JLToolBarHelper::unpublishList('treeto.unpublish');
		JToolBarHelper::divider();

		// @todo check!
		JLToolBarHelper::addNew('treeto.save');
		JLToolBarHelper::deleteList(JText::_('COM_JOOMLEAGUE_ADMIN_TREETOS_WARNING'), 'treeto.remove');
		JToolBarHelper::divider();

		JToolBarHelper::help('screen.joomleague',true);
	}
}
