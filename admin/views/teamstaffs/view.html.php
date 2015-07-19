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
 * @author	Kurt Norgaz
 */
class JoomleagueViewTeamStaffs extends JLGView
{

	public function display( $tpl = null )
	{
		if ( $this->getLayout() == 'default' )
		{
			$this->_displayDefault( $tpl );
			return;
		}

		parent::display( $tpl );
	}

	function _displayDefault( $tpl )
	{
		$app 		= JFactory::getApplication();
		$jinput 	= $app->input;
		
		$document 	= JFactory::getDocument();
		$option 	= $jinput->getCmd('option');
		
		$uri = JFactory::getURI();
	
		$baseurl    = JUri::root();
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Autocompleter.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Autocompleter.Request.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/Observer.js');
		$document->addScript($baseurl.'administrator/components/com_joomleague/assets/js/autocompleter/1_4/quickaddperson.js');
		$document->addStyleSheet($baseurl.'administrator/components/com_joomleague/assets/css/Autocompleter.css');	

		$filter_state		= $app->getUserStateFromRequest( $option . 'ts_filter_state',		'filter_state',		'',				'word' );
		$filter_order		= $app->getUserStateFromRequest( $option . 'ts_filter_order',		'filter_order',		'ppl.ordering',	'cmd' );
		$filter_order_Dir	= $app->getUserStateFromRequest( $option . 'ts_filter_order_Dir',	'filter_order_Dir',	'',				'word' );
		$search				= $app->getUserStateFromRequest( $option . 'ts_search',				'search',			'',				'string' );
		$search_mode		= $app->getUserStateFromRequest( $option . 'ts_search_mode',		'search_mode',		'',				'string' );

		$teamws	= $this->get( 'Data', 'project_team' );
		$app->setUserState( 'team_id', $teamws->team_id );

		$items		= $this->get( 'Data' );
		$total		= $this->get( 'Total' );
		$pagination = $this->get( 'Pagination' );

		$model		= $this->getModel();

		// state filter
		$lists['state'] = JHtml::_( 'grid.state', $filter_state );

		// table ordering
		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		// search filter
		$lists['search'] = $search;
		$lists['search_mode']= $search_mode;

		//build the html options for position
		$position_id[] = JHtml::_( 'select.option', '0', JText::_( 'COM_JOOMLEAGUE_GLOBAL_SELECT_FUNCTION' ) );
		if ($res = $model->getPositions())
		{
			$position_id = array_merge($position_id, $res);
		}
		$lists['project_position_id'] = $position_id;
		unset( $position_id );

		$projectws		= $this->get('Data', 'project');
		$teamstaffws	= $this->get('Data', 'team_staff');

		$this->user = JFactory::getUser();
		$this->lists = $lists;
		$this->items = $items;
		$this->projectws = $projectws;
		$this->teamstaffws = $teamstaffws;
		$this->teamws = $teamws;
		$this->pagination = $pagination;
		$this->request_url = $uri->toString();

		$this->addToolbar();		
		parent::display( $tpl );
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_TITLE' ) );

		JLToolBarHelper::publishList('teamstaff.publish');
		JLToolBarHelper::unpublishList('teamstaff.unpublish');
		JLToolBarHelper::apply( 'teamstaff.saveshort', 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_APPLY' );
		JToolBarHelper::divider();

		JLToolBarHelper::custom( 'teamstaff.assign', 'upload.png', 'upload_f2.png', 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_ASSIGN', false );
		JLToolBarHelper::custom( 'teamstaff.unassign', 'cancel.png', 'cancel_f2.png', 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_UNASSIGN', false );
		JToolBarHelper::divider();

		JToolBarHelper::back( 'COM_JOOMLEAGUE_ADMIN_TSTAFFS_BACK', 'index.php?option=com_joomleague&view=projectteams&task=projectteam.display' );
		JToolBarHelper::divider();

		JToolBarHelper::help( 'screen.joomleague', true );
	}
}
