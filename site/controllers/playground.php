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

class JoomleagueControllerPlayground extends JoomleagueController
{
    public function display($cachable = false, $urlparams = false) 
    {
        // Get the view name from the query string
        $viewName = JRequest::getVar( "view", "playground" );

        // Get the view
        $view = $this->getView( $viewName );

        // Get the joomleague model
        $jl = $this->getModel( "joomleague", "JoomleagueModel" );
        $jl->set( "_name", "joomleague" );
        if (!JError::isError( $jl ) )
        {
            $view->setModel ( $jl );
        }

        // Get the playground model
        $pg = $this->getModel( "playground", "JoomleagueModel" );
        $pg->set( "_name", "playground" );
        if (!JError::isError( $pg ) )
        {
            $view->setModel ( $pg );
        }

        // Get the countries model
        $cn = $this->getModel( "countries", "JoomleagueModel" );
        $cn->set( "_name", "countries" );
        if (!JError::isError( $cn ) )
        {
            $view->setModel ( $cn );
        }

        // Get the Google map model
        $gm = $this->getModel( "googlemap", "JoomleagueModel" );
        $gm->set( "_name", "googlemap" );
        if (!JError::isError( $gm ) )
        {
            $view->setModel ( $gm );
        }

        $this->showprojectheading();
        $view->display();
        $this->showbackbutton();
        $this->showfooter();
    }
}
