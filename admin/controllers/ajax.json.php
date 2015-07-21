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

/**
 * Ajax Controller
 */
class JoomleagueControllerAjax extends JControllerLegacy
{

	public function __construct()
	{
		// Get the document object.
		$document = JFactory::getDocument();
		// Set the MIME type for JSON output.
		$document->setMimeEncoding('application/json');
		parent::__construct();
	}

	public function projectdivisionsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectDivisionsOptions(JRequest::getInt('p'), $required));
		JFactory::getApplication()->close();
	}

	public function projecteventsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectEventsOptions(JRequest::getInt('p'), $required));
		JFactory::getApplication()->close();
	}

	public function projectteamsbydivisionoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectTeamsByDivisionOptions(JRequest::getInt('p'), JRequest::getInt( 'division' ), $required));
		JFactory::getApplication()->close();
	}

	public function projectsbysportstypesoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectsBySportsTypesOptions(JRequest::getInt('sportstype'), $required));
		JFactory::getApplication()->close();
	}

	public function projectsbycluboptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectsByClubOptions(JRequest::getInt( 'cid' ), $required));
		JFactory::getApplication()->close();
	}

	public function projectteamsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		$loaddefault = JRequest::getVar('loaddefault',false);
		echo json_encode((array) $model->getProjectTeamOptions(JRequest::getInt('p'),JRequest::getInt('division'),$required,$loaddefault));
		JFactory::getApplication()->close();
	}
	
	public function projectplayeroptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectPlayerOptions(JRequest::getInt('p'),JRequest::getInt('division'),$required));
		JFactory::getApplication()->close();
	}

	public function projectstaffoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectStaffOptions(JRequest::getInt('p'),JRequest::getInt('division'),$required));
		JFactory::getApplication()->close();
	}

	public function projectclubsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectClubOptions(JRequest::getInt('p'), $required));
		JFactory::getApplication()->close();
	}

	public function projectstatsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectStatOptions(JRequest::getInt('p'), $required));
		JFactory::getApplication()->close();
	}

	public function matchesoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getMatchesOptions(JRequest::getInt('p'),JRequest::getInt('division'), $required));
		JFactory::getApplication()->close();
	}

	public function refereesoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getRefereesOptions(JRequest::getInt('p'), $required));
		JFactory::getApplication()->close();
	}

	public function roundsoptions()
	{
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) JoomleagueHelper::getRoundsOptions(JRequest::getInt('p'),'ASC', $required));
		JFactory::getApplication()->close();
	}

	public function projecttreenodeoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectTreenodeOptions(JRequest::getInt('p'), $required));
		JFactory::getApplication()->close();
	}
	
	public function sportstypesoptions()
	{
		echo json_encode((array) JoomleagueModelSportsTypes::getSportsTypes());
		JFactory::getApplication()->close();
	}

}
