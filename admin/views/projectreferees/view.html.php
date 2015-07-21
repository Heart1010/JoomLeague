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
class JoomleagueViewProjectReferees extends JLGView
{

	public function display($tpl=null)
	{
		if ($this->getLayout() == 'editlist')
		{
			$this->_displayEditlist($tpl);
			return;
		}

		if ($this->getLayout() == 'default')
		{
			$this->_displayDefault($tpl);
			return;
		}

		parent::display($tpl);
	}

	function _displayEditlist($tpl)
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();

		$project_id = $app->getUserState($option.'project');
		$uri = JFactory::getURI();

		$filter_state		= $app->getUserStateFromRequest($option.'p_filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($option.'p_filter_order',		'filter_order',		'p.lastname',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'p_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'p_search',			'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'p_search_mode',		'search_mode',		'',				'string');

		$model			= $this->getModel();
		$projectplayer	= $this->get('Data');
		$total			= $this->get('Total');
		$pagination		= $this->get('Pagination');

		// state filter
		$lists['state']=JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir']		= $filter_order_Dir;
		$lists['order']			= $filter_order;

		// search filter
		$lists['search']		= $search;
		$lists['search_mode']	= $search_mode;

		$projectws = $this->get('Data','project');

		//build the html select list for project assigned players
		$ress=array();
		$res1=array();
		$notusedplayers=array();
		if ($ress = $model->getProjectPlayers())
		{
			foreach ($ress1 as $res1)
			{
				$used=0;
				foreach ($ress as $res)
				{
					if ($res1->value == $res->value)
					{
						$used=1;
					}

				}
				if ($used == 0)
				{
					$notusedplayers[]=JHtml::_('select.option',$res1->value,
					  JoomleagueHelper::formatName(null, $res1->firstname, $res1->nickname, $res1->lastname, 0) .
					  ' ('.$res1->notes.')');
				}
			}
		}
		else
		{
			foreach ($ress1 as $res1)
			{
				$notusedplayers[]=JHtml::_(	'select.option',$res1->value,
				  JoomleagueHelper::formatName(null, $res1->firstname, $res1->nickname, $res1->lastname, 0) .
				  ' ('.$res1->notes.')');
			}
		}

		//build the html select list for players
		if (count ($notusedplayers) > 0)
		{
			$lists['players']=JHtml::_(	'select.genericlist',$notusedplayers,'playerslist[]',
											' style="width:150px" class="inputbox" multiple="true" size="30"','value','text');
		}
		else
		{
				$lists['players']='<select name="playerslist[]" id="playerslist" style="width:150px" class="inputbox" multiple="true" size="10"></select>';
		}
		unset($res);
		unset($res1);
		unset($notusedplayers);

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->projectplayer = $projectplayer;
		$this->projectws = $projectws;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}

	function _displayDefault($tpl)
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$uri = JFactory::getURI();
		$project_id = $app->getUserState($option.'project');
		$team_id = $app->getUserState($option.'team');

		$document = JFactory::getDocument();
		$baseurl    = JUri::root();
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Autocompleter.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Autocompleter.Request.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Observer.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/quickaddperson.js');
		$document->addStyleSheet($baseurl.'administrator/components/com_joomleague/assets/css/Autocompleter.css');	

		$filter_state		= $app->getUserStateFromRequest($option.'p_filter_state',		'filter_state',		'',				'word');
		$filter_order		= $app->getUserStateFromRequest($option.'p_filter_order',		'filter_order',		'p.ordering',	'cmd');
		$filter_order_Dir	= $app->getUserStateFromRequest($option.'p_filter_order_Dir',	'filter_order_Dir',	'',				'word');
		$search				= $app->getUserStateFromRequest($option.'p_search',			'search',			'',				'string');
		$search_mode		= $app->getUserStateFromRequest($option.'p_search_mode',		'search_mode',		'',				'string');

		$items		= $this->get('Data');
		$total		= $this->get('Total');
		$pagination = $this->get('Pagination');
		$model		= $this->getModel();

		// state filter
		$lists['state']=JHtml::_('grid.state',$filter_state);

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']		= $search;
		$lists['search_mode']	= $search_mode;

		//build the html options for position
		$position_id[]=JHtml::_('select.option','0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_REF_FUNCTION'));
		if ($res = $model->getRefereePositions()){
			$position_id=array_merge($position_id,$res);
		}
		$lists['project_position_id']=$position_id;
		unset($position_id);

		$projectws	= $this->get('Data','project');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->items = $items;
		$this->projectws = $projectws;
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
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_PREF_TITLE'),'jl-Referees');
		
		JLToolBarHelper::apply('projectreferee.saveshort','COM_JOOMLEAGUE_ADMIN_PREF_APPLY');
		JLToolBarHelper::custom('projectreferee.assign','upload.png','upload_f2.png','COM_JOOMLEAGUE_ADMIN_PREF_ASSIGN',false);
		JLToolBarHelper::custom('projectreferee.unassign','cancel.png','cancel_f2.png','COM_JOOMLEAGUE_ADMIN_PREF_UNASSIGN',false);
		JToolBarHelper::divider();
		
		JToolBarHelper::help('screen.joomleague',true);
	}
}
