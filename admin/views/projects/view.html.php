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
class JoomleagueViewProjects extends JLGView
{
	public function display($tpl=null)
	{
		$option 	= JRequest::getCmd('option');
		$app		= JFactory::getApplication();
		$uri		= JFactory::getURI();
		$lists		= array();
		
		$filter_league		= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_league',		'filter_league','',					'int');
		$filter_sports_type	= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_sports_type',	'filter_sports_type','',			'int');
		$filter_season		= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_season',		'filter_season','',					'int');
		$filter_state		= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_state',			'filter_state',		'P',			'word');
		$filter_order		= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_order',			'filter_order',		'p.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_order_Dir',		'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.search',				'search',			'',				'string');
		$search=JString::strtolower($search);
		
		// Get data from the model
		$items		= $this->get('Data');
		$total		= $this->get('Total');
		$pagination = $this->get('Pagination');
		$javascript = "onchange=\"$('adminForm').submit();\"";

		// state filter
		$lists['state'] = JHtml::_('grid.state',$filter_state, 'Published', 'Unpublished', 'Archived', 'Trashed');

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;

		//build the html select list for leagues
		$leagues[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_LEAGUES_FILTER'),'id','name');
		$mdlLeagues = JModelLegacy::getInstance('Leagues','JoomleagueModel');
		$allLeagues = $mdlLeagues->getLeagues();
		$leagues=array_merge($leagues,$allLeagues);
		$lists['leagues']=JHtml::_( 'select.genericList',
									$leagues,
									'filter_league',
									'class="inputbox" onChange="this.form.submit();" style="width:120px"',
									'id',
									'name',
									$filter_league);
		unset($leagues);
		
		
		//build the html select list for sportstypes
		$sportstypes[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_SPORTSTYPE_FILTER'),'id','name');
		$mdlSportsTypes = JModelLegacy::getInstance('SportsTypes', 'JoomleagueModel');
		$allSportstypes = $mdlSportsTypes->getSportsTypes();
		$sportstypes=array_merge($sportstypes,$allSportstypes);
		$lists['sportstypes']=JHtml::_( 'select.genericList',
										$sportstypes,
										'filter_sports_type',
										'class="inputbox" onChange="this.form.submit();" style="width:120px"',
										'id',
										'name',
										$filter_sports_type);
		unset($sportstypes);
		
		
		//build the html select list for seasons
		$seasons[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_SEASON_FILTER'),'id','name');

		if ($res = $this->get('Seasons')){$seasons=array_merge($seasons,$res);}
		
		$lists['seasons']=JHtml::_( 'select.genericList',
									$seasons,
									'filter_season',
									'class="inputbox" onChange="this.form.submit();" style="width:120px"',
									'id',
									'name',
									$filter_season);

		unset($seasons);
		$user = JFactory::getUser();
		$this->user = $user;
		$this->lists = $lists;
		$this->items = $items;
		$this->pagination = $pagination;
		
		$url=$uri->toString();
		$this->request_url = $url;
		
		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{ 
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PROJECTS_TITLE'),'ProjectSettings');
		JLToolBarHelper::addNew('project.add');
		JLToolBarHelper::custom('project.copy','copy.png','copy_f2.png','COM_JOOMLEAGUE_GLOBAL_COPY',false);
		JLToolBarHelper::editList('project.edit');
		JToolBarHelper::divider();
		JLToolBarHelper::publishList('project.publish');
		JLToolBarHelper::unpublishList('project.unpublish');
		JToolBarHelper::divider();
		
		JLToolBarHelper::custom('project.import','upload','upload','COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT',false);
		JLToolBarHelper::archiveList('project.export','COM_JOOMLEAGUE_GLOBAL_XML_EXPORT');
		JToolBarHelper::divider();
		JLToolBarHelper::archiveList('project.archive');
		JLToolBarHelper::trash('project.trash');
		JLToolBarHelper::deleteList('COM_JOOMLEAGUE_ADMIN_PROJECTS_DELETE_WARNING', 'project.remove');
		JToolBarHelper::divider();
		
		JToolBarHelper::help('screen.joomleague',true);
	}
}
