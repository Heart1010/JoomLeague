<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;

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
