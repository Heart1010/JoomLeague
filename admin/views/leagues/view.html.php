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
class JoomleagueViewLeagues extends JLGView
{
	public function display($tpl=null)
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();

		$filter_order		= $app->getUserStateFromRequest($option.'l_filter_order',		'filter_order',		'obj.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'l_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'l_search',			'search',			'',				'string');
		$search=JString::strtolower($search);

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		// search filter
		$lists['search']=$search;

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_LEAGUES_TITLE'),'jl-leagues');
		JLToolBarHelper::addNew('league.add');
		JLToolBarHelper::editList('league.edit');
		JLToolBarHelper::custom('league.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('league.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JLToolBarHelper::deleteList('', 'league.remove');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}
}
