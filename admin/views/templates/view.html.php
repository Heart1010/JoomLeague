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
 *
 * @author	Marco Vaninetti <martizva@tiscali.it>
 */
class JoomleagueViewTemplates extends JLGView
{
	public function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$jinput = $app->input;
		
		$option = $jinput->getCmd('option');
		$document = JFactory::getDocument();
		$uri = JFactory::getURI();
		$templates = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');
		$projectws = $this->get('Data','project');
		
		$model=$this->getModel();
		if ($projectws->master_template)
		{
			$model->set('_getALL',1);
			$allMasterTemplates=$this->get('MasterTemplatesList');
			$model->set('_getALL',0);
			$masterTemplates=$this->get('MasterTemplatesList');
			$importlist=array();
			$importlist[]=JHtml::_('select.option',0,JText::_('COM_JOOMLEAGUE_ADMIN_TEMPLATES_SELECT_FROM_MASTER'));
			$importlist=array_merge($importlist,$masterTemplates);
			$lists['mastertemplates']=JHtml::_('select.genericlist',$importlist,'templateid');
			$master=$this->get('MasterName');
			$this->master = $master;
			$templates=array_merge($templates,$allMasterTemplates);
		}

		$filter_state		= $app->getUserStateFromRequest($option.'tmpl_filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($option.'tmpl_filter_order',		'filter_order',		'tmpl.template','cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'tmpl_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'tmpl_search',			'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'tmpl_search_mode',		'search_mode',		'',				'string');
		$search				= JString::strtolower($search);

		// state filter
		$lists['state']=JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		// search filter
		$lists['search']=$search;
		$lists['search_mode']=$search_mode;

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->templates = $templates;
		$this->projectws = $projectws;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		$this->projectws = $projectws;
		
		$this->addToolbar();			
		parent::display($tpl);
	}
	/**
	* Add the page title and toolbar
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_TEMPLATES_TITLE'),'FrontendSettings');
		JLToolBarHelper::editList('template.edit');
		JLToolBarHelper::save('template.save');
		if ($this->projectws->master_template)
		{
			JLToolBarHelper::deleteList('','template.remove');
		}
		else
		{
			JLToolBarHelper::custom('template.reset','restore','restore',JText::_('COM_JOOMLEAGUE_GLOBAL_RESET'));
		}
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}	
}
