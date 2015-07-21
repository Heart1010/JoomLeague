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

class JoomleagueControllerTeams extends JoomleagueController
{
    public function display($cachable = false, $urlparams = false)
    {
        // Get the view name from the query string
        $viewName = JRequest::getVar( "view", "teams" );

        // Get the view
        $view = $this->getView( $viewName );

        // Get the joomleague model
        $jl = $this->getModel( "joomleague", "JoomleagueModel" );
        $jl->set( "_name", "joomleague" );
        if (!JError::isError( $jl ) )
        {
            $view->setModel ( $jl );
        }

        // Get the teamsoverview model
        $sc = $this->getModel( "teams", "JoomleagueModel" );
        $sc->set( "_name", "teamslist" );
        if (!JError::isError( $sc ) )
        {
            $view->setModel ( $sc );
        }

        $this->showprojectheading();
        $view->display();
        $this->showbackbutton();
        $this->showfooter();
    }
}
