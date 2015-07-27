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
class JoomleagueViewStatistics extends JLGView
{
	public function display($tpl=null)
	{
		$app 		= JFactory::getApplication();
		$jinput 	= $app->input;
		$option 	= $jinput->getCmd('option');
		
		$document	= JFactory::getDocument();
		$user 		= JFactory::getUser();
		$uri 		= JFactory::getURI();
		
		$filter_sports_type	= $app->getUserStateFromRequest($this->get('context').'.filter_sports_type',	'filter_sports_type','',	'int');
		$filter_state		= $app->getUserStateFromRequest($this->get('context').'.filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($this->get('context').'.filter_order',		'filter_order',		'obj.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($this->get('context').'.filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($this->get('context').'.search',			'search',			'',				'string');
		$search				= JString::strtolower($search);

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		// state filter
		$lists['state'] = JoomleagueHelper::stateOptions($filter_state);

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;
		
				//build the html select list for sportstypes
		$sportstypes[] = JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_EVENTS_SPORTSTYPE_FILTER'),'id','name');
		$allSportstypes = JoomleagueModelSportsTypes::getSportsTypes();
		$sportstypes = array_merge($sportstypes,$allSportstypes);
		$lists['sportstypes'] = JHtml::_( 'select.genericList',
										$sportstypes,
										'filter_sports_type',
										'class="input-medium" onChange="this.form.submit();"',
										'id',
										'name',
										$filter_sports_type);
		unset($sportstypes);

		$this->user = $user;
		$this->config = JFactory::getConfig();
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
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_STATISTICS_TITLE'),'jl-statistics');
			
		JLToolBarHelper::publishList();
		JLToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JLToolBarHelper::editList('statistic.edit');
		JLToolBarHelper::addNew('statistic.add');
		JLToolBarHelper::custom('statistic.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('statistic.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JLToolBarHelper::deleteList(JText::_('COM_JOOMLEAGUE_ADMIN_STATISTICS_DELETE_WARNING'),'statistic.fulldelete','COM_JOOMLEAGUE_ADMIN_STATISTICS_FULL_DELETE');
		JToolBarHelper::divider();
		
		JToolBarHelper::help('screen.joomleague',true);
	}
}
