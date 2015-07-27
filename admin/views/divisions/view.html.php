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
class JoomleagueViewDivisions extends JLGView
{

	public function display($tpl = null)
	{
		$option = $this->input->getCmd('option');

		$app	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$uri	= JFactory::getURI();

		$filter_state		= $app->getUserStateFromRequest($this->get('context').'.filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($this->get('context').'.filter_order',		'filter_order',		'dv.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->get('context').'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($this->get('context').'.search',			'search',			'',				'string');
		$search				= JString::strtolower($search);

		$items		= $this->get('Data');
		$total		= $this->get('Total');
		$pagination = $this->get('Pagination');

		$projectws	= $this->get('Data', 'project');

		// state filter
		$lists['state']		= JHtml::_('grid.state',  $filter_state);

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']	= $search;

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->items = $items;
		$this->projectws = $projectws;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		$this->addToolbar();
		parent::display( $tpl );
	}
	
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_DIVS_TITLE'));
		
		JLToolBarHelper::addNewX('division.add');
		JLToolBarHelper::editListX('division.edit');
		JLToolBarHelper::deleteList(JText::_('COM_JOOMLEAGUE_ADMIN_DIVISIONS_DELETE_WARNING'), 'division.remove');
		JToolBarHelper::divider();
		
		JToolBarHelper::help('screen.joomleague', true);
	}
}
