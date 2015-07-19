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
class JoomleagueViewClubs extends JLGView
{

	public function display($tpl=null)
	{
		$option 	= $this->input->getCmd('option');
		$params		= JComponentHelper::getParams( $option );
		$app 		= JFactory::getApplication();
		$uri		= JFactory::getURI();

		$filter_state		= $app->getUserStateFromRequest($option.'a_filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($option.'a_filter_order',		'filter_order',		'a.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'a_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'a_search',				'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'a_search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);

		$items		= $this->get('Data');
		$total		= $this->get('Total');
		$pagination = $this->get('Pagination');

		// state filter
		$lists['state'] = JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;
		$lists['search_mode'] = $search_mode;

		$this->user = JFactory::getUser();
		$this->config = JFactory::getConfig();
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		$this->component_params = $params;
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_CLUBS_TITLE'),'clubs');

		JLToolBarHelper::addNew('club.add');
		JLToolBarHelper::editList('club.edit');
		JLToolBarHelper::custom('club.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('club.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JLToolBarHelper::deleteList('', 'club.remove');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}
}
