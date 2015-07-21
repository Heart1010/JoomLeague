<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('JPATH_BASE') or die;

jimport('joomla.application.component.controller');

class JoomleagueControllerAbout extends JLGController
{
	public function display($cachable = false, $urlparams = false)
	{
		// Get the view name from the query string
		$viewName = JRequest::getVar( "view", "about" );

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
		$vr = $this->getModel( "version", "JoomleagueModel" );
		$vr->set( "_name", "version" );
		if (!JError::isError( $vr ) )
		{
			$view->setModel ( $vr );
		}

		// Display view
		// $this->showprojectheading(); No heading -> specific heading
		$view->display();
		$this->showbackbutton();
		$this->showfooter();
	}
}
