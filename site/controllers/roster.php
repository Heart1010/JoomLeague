<?php
/**
 * @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.controller' );

class JoomleagueControllerRoster extends JoomleagueController
{
	public function display($cachable = false, $urlparams = false)
	{
		// Get the view name from the query string
		$viewName = JRequest::getVar( 'view', 'roster' );

		// Get the view
		$view = $this->getView( $viewName );

		// Get the joomleague model
		$jl = $this->getModel( 'project', 'JoomleagueModel' );
		$jl->set( '_name', 'project' );
		if (!JError::isError( $jl ) )
		{
			$view->setModel ( $jl );
		}
		
		// Get the joomleague model
		$sr = $this->getModel( 'roster', 'JoomleagueModel' );
		$sr->set( '_name', 'roster' );
		if ( !JError::isError( $sr ) )
		{
			$view->setModel ( $sr );
		}

		$this->showprojectheading();
		$view->display();
		$this->showbackbutton();
		$this->showfooter();
	}

	public function favplayers()
	{
		$db  = JFactory::getDbo();
		$jlm = $this->getModel( 'project', 'JoomleagueModel' );
		$jl = $jlm->getProject();

		$favteam = explode( ',', $jl->fav_team );
		if ( count( $favteam ) == 1 )
		{
			$teamid = $favteam[0];
			$query = 'SELECT id
					  FROM #__joomleague_project_team tt
					  WHERE tt.project_id = ' . $jl->id . '
					  AND tt.team_id = ' . $teamid;

			$db->setQuery( $query );
			$projectteamid = $db->loadResult();

			JRequest::setVar( 'ttid', $projectteamid );
		}

		$this->display();
	}

}
?>