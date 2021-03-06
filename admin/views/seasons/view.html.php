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
class JoomleagueViewSeasons extends JLGView
{
	public function display($tpl=null)
	{
		$option = JRequest::getCmd('option');
		$mainframe = JFactory::getApplication();
		$uri = JFactory::getURI();
		$lists		= array();
		
		$filter_order		= $mainframe->getUserStateFromRequest($option.'s_filter_order',		'filter_order',		's.ordering',	'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.'s_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$filter_state		= $mainframe->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_state',			'filter_state',		'P',			'word');
		$search				= $mainframe->getUserStateFromRequest($option.'s_search',			'search',			'',				'string');
		$search				= JString::strtolower($search);

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		// state filter
		$lists['state'] = JHtml::_('grid.state',$filter_state, 'Published', 'Unpublished', 'Archived', 'Trashed');

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
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_SEASONS_TITLE'),'seasons');
		JLToolBarHelper::addNew('season.add');
		JLToolBarHelper::editList('season.edit');
		JToolBarHelper::divider();
		JLToolBarHelper::publishList('season.publish');
		JLToolBarHelper::unpublishList('season.unpublish');
		JToolBarHelper::divider();
		JLToolBarHelper::custom('season.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('season.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JToolBarHelper::divider();
		JLToolBarHelper::archiveList('season.archive');
		JLToolBarHelper::trash('season.trash');
		JLToolBarHelper::deleteList('', 'season.remove');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}
}
