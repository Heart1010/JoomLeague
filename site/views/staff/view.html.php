<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JoomleagueViewStaff extends JLGView
{

	function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();

		$model = $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		$person = $model->getPerson();
		$project = $model->getProject();

		$current_round = $project->current_round;
		$personid = $model->personid;
		
		$this->assignRef('project',$model->getProject());
		$this->assignRef('overallconfig',$model->getOverallConfig());
		$this->assignRef('config',$config);
		$this->assignRef('person',$person);
		
		$staff=&$model->getTeamStaffByRound($current_round, $personid);
		
		$this->assignRef('teamStaff',$staff);
		$this->assignRef('history',$model->getStaffHistory('ASC'));
		
		$this->assignRef('stats',$model->getStats($current_round, $personid));
		$this->assignRef('staffstats',$model->getStaffStats($current_round, $personid));
		$this->assignRef('historystats',$model->getHistoryStaffStats($current_round, $personid));
		$this->assignRef('showediticon',$model->getAllowed($config['edit_own_player']));
		$extended = $this->getExtended($person->extended, 'staff');
		$this->assignRef( 'extended', $extended);
		
		if (isset($person))
		{
			$name = JoomleagueHelper::formatName(null, $person->firstname, $person->nickname, $person->lastname, $this->config["name_format"]);
		}
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_STAFF_PAGE_TITLE'));
		$titleInfo->personName = $name;
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
		$this->assignRef('pagetitle', JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]));
		$document->setTitle($this->pagetitle);
		
		parent::display($tpl);
	}

}
?>