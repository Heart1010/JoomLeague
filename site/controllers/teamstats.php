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

class JoomleagueControllerTeamStats extends JoomleagueController
{
    public function display($cachable = false, $urlparams = false)
    {
        // Get the view name from the query string
        $viewName = JRequest::getVar( "view", "teamstats" );

        // Get the view
        $view = $this->getView( $viewName );

        // Get the joomleague model
        $jl = $this->getModel( "joomleague", "JoomleagueModel" );
        $jl->set( "_name", "joomleague" );
        if (!JError::isError( $jl ) )
        {
            $view->setModel ( $jl );
        }

        // Get the joomleague model
        $sr = $this->getModel( "teamstats", "JoomleagueModel" );
        $sr->set( "_name", "teamstats" );
        if (!JError::isError( $sr ) )
        {
            $view->setModel ( $sr );
        }
        
        // Get the joomleague model
        $sr = $this->getModel( "eventsranking", "JoomleagueModel" );
        $sr->set( "_name", "eventsranking" );
        if (!JError::isError( $sr ) )
        {
            $view->setModel ( $sr );
        }
        
        $this->showprojectheading();
        $view->display();
        $this->showbackbutton();
        $this->showfooter();
    }     
    
    function chartdata()
    {
        // Get the view name from the query string
        $viewName = JRequest::getVar( "view", "teamstats" );

        // Get the view
        $view = $this->getView( $viewName );

        // Get the joomleague model
        $jl = $this->getModel( "joomleague", "JoomleagueModel" );
        $jl->set( "_name", "joomleague" );
        if (!JError::isError( $jl ) )
        {
            $view->setModel ( $jl );
        }

        // Get the joomleague model
        $sr = $this->getModel( "teamstats", "JoomleagueModel" );
        $sr->set( "_name", "teamstats" );
        if (!JError::isError( $sr ) )
        {
            $view->setModel ( $sr );
        }
        
        // Get the joomleague model
        $sr = $this->getModel( "eventsranking", "JoomleagueModel" );
        $sr->set( "_name", "eventsranking" );
        if (!JError::isError( $sr ) )
        {
            $view->setModel ( $sr );
        }
        
        $view->setLayout( "chartdata" );
        $view->display();
    }
}
