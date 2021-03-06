<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class JoomleagueViewPerson extends JLGView
{

	function display( $tpl = null )
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();

		$model	= $this->getModel();
		$config = $model->getTemplateConfig("player");

		// Get the type of persondata to be shown from the query string
		// pt==1 ==> as player // pt==2 ==> as staffmember  // pt==3 ==> as referee // pt==4 ==> as club-staffmember
		$showType = JRequest::getVar( 'pt', '1', 'default', 'int' ); if ($showType > 3) { $showType = 1; }
		$person = $model->getPerson();
		$this->showType = $showType;
		$this->project = $model->getProject();
		$this->overallconfig = $model->getOverallConfig();
		$this->config = $config;
		$this->person = $person;
		//$extended = $this->getExtended($person->extended, 'person');
		//$this->extended = $extended;
		switch ($showType) 
		{
			case '4':
				$titleStr = 'About %1$s %2$s as a Club staff';
				$this->historyClubStaff = $model->getPersonHistory('ASC');
				break;
			case '3':
				$titleStr = 'About %1$s %2$s as a Referee';
				$this->inprojectinfo = $model->getReferee();
				$this->historyReferee = $model->getRefereeHistory('ASC');
				break;
			case '2':
				$titleStr = 'About %1$s %2$s as a Staff member';
				$this->inprojectinfo = $model->getTeamStaff();
				$this->historyStaff = $model->getStaffHistory('ASC');
				break;
			case '1':
				$titleStr = 'About %1$s %2$s as a Player';
				$this->inprojectinfo = $model->getTeamPlayer();
  				$this->historyPlayer = $model->getPlayerHistory('ASC');
  				//$this->historyStaff = $model->getStaffHistory('ASC');
				$this->AllEvents = $model->getAllEvents();
				break;
			default:
				break;
		}

		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::sprintf( $titleStr, $this->person->firstname, $this->person->lastname ));
		if (!empty($this->project))
		{
			$titleInfo->projectName = $this->project->name;
			$titleInfo->leagueName = $this->project->league_name;
			$titleInfo->seasonName = $this->project->season_name;
		}
		$division = $model->getDivision(JRequest::getInt('division',0));
		if (!empty( $division ) && $division->id != 0)
		{
			$titleInfo->divisionName = $division->name;
		}
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]);
		$document->setTitle($this->pagetitle);
		
		parent::display( $tpl );
	}
}
