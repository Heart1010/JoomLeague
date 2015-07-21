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

class JoomleagueController extends JLGController
{
	public function display($cachable = false, $urlparams = false)
	{
		$this->showprojectheading( $cachable );
	}

	function showprojectheading( $cachable = false )
	{
		parent::display();
	}

	function showbackbutton( )
	{
		// Get the view name from the query string
		$viewName = JRequest::getVar( 'view', 'backbutton' );

		// Get the view
		$view = $this->getView( $viewName );

		// Get the joomleague model
		$mdlJoomleague = $this->getModel( 'project', 'JoomleagueModel' );
		$mdlJoomleague->set( '_name', 'project' );
		if (!JError::isError( $mdlJoomleague ) )
		{
			$view->setModel ( $mdlJoomleague );
		}

		$view->display();
	}

	function showfooter( )
	{
		parent::display();
	}
}
