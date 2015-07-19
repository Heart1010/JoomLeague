<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class
 */
class JoomleagueViewProjectposition extends JLGView
{

	public function display($tpl=null)
	{
	 	if ($this->getLayout()=='editlist')
		{
			$this->_displayEditlist($tpl);
			return;
		}

		if ($this->getLayout()=='default')
		{
			$this->_displayDefault($tpl);
			return;
		}

		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$filter_state		= $app->getUserStateFromRequest($option.'pt_filter_state',		'filter_state',		'',			'word');
		$filter_order		= $app->getUserStateFromRequest($option.'pt_filter_order',		'filter_order',		'po.name',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'pt_filter_order_Dir',	'filter_order_Dir',	'',			'word');
		$search				= $app->getUserStateFromRequest($option.'pt_search',				'search',			'',			'string');
		$search_mode		= $app->getUserStateFromRequest($option.'pt_search_mode',			'search_mode',		'',			'string');
		$search				= JString::strtolower($search);
		$positiontool = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');
		$model = $this->getModel();

		// state filter
		$lists['state']=JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir']=$filter_order_Dir;
		$lists['order']=$filter_order;

		// search filter
		$lists['search']=$search;
		$lists['search_mode']=$search_mode;

		$projectws = $this->get('Data','project');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->positiontool = $positiontool;
		$this->projectws = $projectws;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();
		
		$this->addToolbar();	
		parent::display($tpl);
	}

	function _displayEditlist($tpl)
	{
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$model = $this->getModel();
		$projectws = $this->get('Data','project');

		//build the html select list for project assigned positions
		$ress=array();
		$res1=array();
		$notusedpositions=array();

		if ($ress = $model->getProjectPositions())
		{ // select all already assigned positions to the project
			foreach($ress as $res){$project_positionslist[]=JHtml::_('select.option',$res->value,JText::_($res->text));}
			$lists['project_positions']=JHtml::_(	'select.genericlist',
													$project_positionslist,
													'project_positionslist[]',
													' style="width:250px; height:250px;" class="inputbox" multiple="true" size="'.max(15,count($ress)).'"',
													'value',
													'text');
		}
		else
		{
			$lists['project_positions']='<select name="project_positionslist[]" id="project_positionslist" style="width:250px; height:250px;" class="inputbox" multiple="true" size="10"></select>';
		}

		if ($ress1 = $model->getSubPositions($projectws->sports_type_id))
		{
			if ($ress)
			{
				foreach ($ress1 as $res1)
				{
					if (!in_array($res1,$ress))
					{
						$res1->text=JText::_($res1->text);
						$notusedpositions[]=$res1;
					}
				}
			}
			else
			{
				foreach ($ress1 as $res1)
				{
					$res1->text=JText::_($res1->text);
					$notusedpositions[]=$res1;
				}
			}
		}
		else
		{
			JError::raiseWarning('ERROR_CODE','<br />'.JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_ASSIGN_POSITIONS_FIRST').'<br /><br />');
		}

		//build the html select list for positions
		if (count ($notusedpositions) > 0)
		{
			$lists['positions']=JHtml::_(	'select.genericlist',
											$notusedpositions,
											'positionslist[]',
											' style="width:250px; height:250px;" class="inputbox" multiple="true" size="'.min(15,count($notusedpositions)).'"',
											'value',
											'text');
		}
		else
		{
			$lists['positions']='<select name="positionslist[]" id="positionslist" style="width:250px; height:250px;" class="inputbox" multiple="true" size="10"></select>';
		}
		unset($ress);
		unset($ress1);
		unset($notusedpositions);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		/* $this->positiontool = $positiontool; */
		$this->projectws = $projectws;
		/* $this->pagination = $pagination; */
		$this->request_url = $uri->toString();

		$this->addToolbar_Editlist();		
		parent::display($tpl);
	}
	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{ 
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_TITLE'),'Positions');

		JLToolBarHelper::custom('projectposition.assign','upload.png','upload_f2.png','COM_JOOMLEAGUE_ADMIN_P_POSITION_BUTTON_UN_ASSIGN',false);
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);

	}
	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar_Editlist()
	{ 
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_P_POSITION_EDIT_TITLE'),'Positions');
		JLToolBarHelper::save('projectposition.save_positionslist');
		JLToolBarHelper::cancel('projectposition.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		JToolBarHelper::help('screen.joomleague',true);
	}	
}
