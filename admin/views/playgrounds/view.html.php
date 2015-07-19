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
class JoomleagueViewPlaygrounds extends JLGView
{

	public function display($tpl=null)
	{
		$option = $this->input->getCmd('option');
		$params		= JComponentHelper::getParams($option);
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();

		$filter_order		= $app->getUserStateFromRequest($option.'v_filter_order',		'filter_order',		'v.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'v_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'v_search',				'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'t_search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		// search filter
		$lists['search']=$search;
		$lists['search_mode']=$search_mode;

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
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PLAYGROUNDS_TITLE'),'playground');
		JLToolBarHelper::editList('playground.edit');
		JLToolBarHelper::addNew('playground.add');
		JLToolBarHelper::custom('playground.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('playground.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JLToolBarHelper::deleteList('','playground.remove');
		JToolBarHelper::divider();

		JToolBarHelper::help('screen.joomleague',true);
	}
}
