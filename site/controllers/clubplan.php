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

class JoomleagueControllerClubPlan extends JoomleagueController
{
	public function display($cachable = false, $urlparams = false)
	{
		// Get the view name from the query string
		$viewName=JRequest::getVar("view","clubplan");
		$startdate=JRequest::getVar("startdate",null);
		$enddate=JRequest::getVar("enddate",null);

		// Get the view
		$view = $this->getView($viewName);

		// Get the joomleague model
		$jl = $this->getModel("joomleague","JoomleagueModel");
		$jl->set("_name","joomleague");
		if (!JError::isError($jl)){$view->setModel($jl);}

		$mdlClubPlan = $this->getModel("clubplan","JoomleagueModel");
		$mdlClubPlan->set("_name","clubplan");
		$mdlClubPlan->setStartDate($startdate);
		$mdlClubPlan->setEndDate($enddate);
		if (!JError::isError($mdlClubPlan)){$view->setModel($mdlClubPlan);}

		$this->showprojectheading();
		$view->display();
		$this->showbackbutton();
		$this->showfooter();
	}
}
