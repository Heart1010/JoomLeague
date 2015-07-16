<?php
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Joomleague component
 *
 * @static
 * @package	JoomLeague
 */
class JoomleagueViewPlaygrounds extends JLGView
{

	function display($tpl=null)
	{
		$option = JRequest::getCmd('option');
		$params		= JComponentHelper::getParams( $option );
		$mainframe = JFactory::getApplication();
		$uri = JFactory::getURI();

		$filter_order		= $mainframe->getUserStateFromRequest($option.'v_filter_order',		'filter_order',		'v.ordering',	'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'v_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $mainframe->getUserStateFromRequest($option.'v_search',			'search',			'',				'string');
		$search_mode		= $mainframe->getUserStateFromRequest($option.'t_search_mode',		'search_mode',		'',				'string');
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
