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

class JoomleagueControllerMatrix extends JoomleagueController
{
    public function display($cachable = false, $urlparams = false)
    {
        $this->showprojectheading();
        $this->showmatrix();
        $this->showbackbutton();
        $this->showfooter();
    }

    public function showmatrix( )
    {
        // Get the view name from the query string
        $viewName = JRequest::getVar( "view", "matrix" );

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
        $sm = $this->getModel( "matrix", "JoomleagueModel" );
        $sm->set( "_name", "matrix" );
        if (!JError::isError( $sm ) )
        {
            $view->setModel ( $sm );
        }

        $view->display();
    }
}
