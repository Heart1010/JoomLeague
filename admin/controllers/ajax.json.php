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

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Joomleague Ajax Controller
 *
 * @package		Joomleague
 * @since 1.5
 */
class JoomleagueControllerAjax extends JController
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
	}

	public function projecteventsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectEventsOptions(JRequest::getInt('p'), $required));
	}

	public function projectteamsbydivisionoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectTeamsByDivisionOptions(JRequest::getInt('p'), JRequest::getInt( 'division' ), $required));
	}

	public function projectsbysportstypesoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectsBySportsTypesOptions(JRequest::getInt('sportstype'), $required));
	}

	public function projectsbycluboptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectsByClubOptions(JRequest::getInt( 'cid' ), $required));
	}

	public function projectteamsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectTeamOptions(JRequest::getInt('p'),JRequest::getInt('division'),$required));
	}
	
	public function projectplayeroptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectPlayerOptions(JRequest::getInt('p'),JRequest::getInt('division'),$required));
	}

	public function projectstaffoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectStaffOptions(JRequest::getInt('p'),JRequest::getInt('division'),$required));
	}

	public function projectclubsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectClubOptions(JRequest::getInt('p'), $required));
	}

	public function projectstatsoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectStatOptions(JRequest::getInt('p'), $required));
	}

	public function matchesoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getMatchesOptions(JRequest::getInt('p'),JRequest::getInt('division'), $required));
	}

	public function refereesoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getRefereesOptions(JRequest::getInt('p'), $required));
	}

	public function roundsoptions()
	{
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) JoomleagueHelper::getRoundsOptions(JRequest::getInt('p'),'ASC', $required));
	}

	public function projecttreenodeoptions()
	{
		$model = $this->getModel('ajax');
		$req = JRequest::getVar('required', false);
		$required = ($req == 'true' || $req == '1') ? true : false;
		echo json_encode((array) $model->getProjectTreenodeOptions(JRequest::getInt('p'), $required));
	}
	
	public function sportstypesoptions()
	{
		echo json_encode((array) JoomleagueModelSportsTypes::getSportsTypes());
	}

}
