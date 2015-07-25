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
class JoomleagueViewPositions extends JLGView
{
	public function display($tpl=null)
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$model = $this->getModel();
		
		$filter_sports_type	= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_sports_type',	'filter_sports_type','',			'int');
		$filter_state		= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_state',			'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_order',			'filter_order',		'po.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.filter_order_Dir',		'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'.'.$this->get('identifier').'.search',				'search',			'',				'string');
		$search=JString::strtolower($search);

		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');

		// state filter
		$lists['state'] = JoomleagueHelper::stateOptions($filter_state);

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		// search filter
		$lists['search']=$search;

		//build the html options for parent position
		$parent_id[]=JHtml::_('select.option','',JText::_('COM_JOOMLEAGUE_ADMIN_POSITIONS_IS_P_POSITION'));
		if ($res = $model->getParentsPositions())
		{
			foreach ($res as $re){$re->text=JText::_($re->text);}
			$parent_id=array_merge($parent_id,$res);
		}
		$lists['parent_id']=$parent_id;
		unset($parent_id);

		//build the html select list for sportstypes
		$sportstypes[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_ADMIN_POSITIONS_SPORTSTYPE_FILTER'),'id','name');
		$allSportstypes = JoomleagueModelSportsTypes::getSportsTypes();
		$sportstypes=array_merge($sportstypes,$allSportstypes);
		$lists['sportstypes']=JHtml::_( 'select.genericList',
										$sportstypes,
										'filter_sports_type',
										'class="input-medium" onChange="this.form.submit();"',
										'id',
										'name',
										$filter_sports_type);
		unset($sportstypes);
		$this->user = JFactory::getUser();
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
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_POSITIONS_TITLE'),'jl-Positions');

		JLToolBarHelper::publishList('position.publish');
		JLToolBarHelper::unpublishList('position.unpublish');
		JToolBarHelper::divider();

		JLToolBarHelper::apply('position.saveshort');
		JLToolBarHelper::editList('position.edit');
		JLToolBarHelper::addNew('position.add');
		JLToolBarHelper::custom('position.import','upload','upload',JText::_('COM_JOOMLEAGUE_GLOBAL_CSV_IMPORT'),false);
		JLToolBarHelper::archiveList('position.export',JText::_('COM_JOOMLEAGUE_GLOBAL_XML_EXPORT'));
		JLToolBarHelper::deleteList('','position.remove');
		JToolBarHelper::divider();

		JToolBarHelper::help('screen.joomleague',true);
	}
}
