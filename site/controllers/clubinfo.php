<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class JoomleagueControllerClubInfo extends JoomleagueController
{
	public function display($cachable = false, $urlparams = false)
	{
		// Get the view name from the query string
		$viewName = JRequest::getVar( "view", "clubinfo" );

		// Get the view
		$view = $this->getView( $viewName );

		// Get the joomleague model
		$jl = $this->getModel( "joomleague", "JoomleagueModel" );
		$jl->set( "_name", "joomleague" );
		if (!JError::isError( $jl ) )
		{
			$view->setModel ( $jl );
		}

		// Get the model
		$sc = $this->getModel( "clubinfo", "JoomleagueModel" );
		$sc->set( "_name", "clubinfo" );
		if (!JError::isError( $sc ) )
		{
			$view->setModel ( $sc );
		}

		$this->showprojectheading();
		$view->display();
		$this->showbackbutton();
		$this->showfooter();
	}

	public function save( )
	{
		// Check for request forgeries
		JSession::checkToken() or die( 'COM_JOOMLEAGUE_GLOBAL_INVALID_TOKEN' );
		
		$cid = JRequest::getInt( "cid", 0 );
		$post = JRequest::get( 'post' );

		if( $cid > 0 )
		{
			$club = & JTable::getInstance( "Club", "Table" );
			$club->load( $cid );
			$club->bind( $post );
			$params = JComponentHelper::getParams('com_joomleague');

			if ( ( $club->store() ) &&
			( $params->get('cfg_edit_club_info_update_notify') == "1" ) )
			{
				$user = JFactory::getUser();
	
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('u.email');
				$query->from('#__users AS u');
				$query->leftJoin('#__user_usergroup_map AS map ON map.user_id = u.id');
				$query->leftJoin('#__usergroups AS g ON g.id = map.group_id');
				$query->where('g.title = '.$db->quote("Super Users").' OR g.title = '.$db->quote("Administrator"));
				$query->order('u.username ASC');
				$db->setQuery($query);
				$to = $db->loadColumn();

				$subject = addslashes(
				sprintf(JText::_( "COM_JOOMLEAGUE_ADMIN_EDIT_CLUB_INFO_SUBJECT" ),$club->name ) );
				$message = addslashes(sprintf(JText::_( "COM_JOOMLEAGUE_ADMIN_EDIT_CLUB_INFO_MESSAGE" ),$user->name,$club->name ) );
				$message .= $this->_getShowClubInfoLink();

				JMail::sendMail( '', '', $to, $subject, $message );
			}
		}
		$this->setRedirect( $this->_getShowClubInfoLink() );
	}

	public function cancel( )
	{
		$this->setRedirect( $this->_getShowClubInfoLink() );
	}

	private function _getShowClubInfoLink( )
	{
		$p = JRequest::getInt( "p", 0 );
		$cid = JRequest::getInt( "cid", 0 );
		$link = JoomleagueHelperRoute::getClubInfoRoute( $p, $cid );
		return $link;
	}
}
