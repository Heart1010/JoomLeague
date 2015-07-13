<?php
/**
 * @copyright	Copyright (C) 2005-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die( 'Restricted access' );

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
?>
