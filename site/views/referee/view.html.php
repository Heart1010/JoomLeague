<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class JoomleagueViewReferee extends JLGView
{

	function display($tpl=null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();
		$model = $this->getModel();
		$isEditor = $model->hasEditPermission('projectreferee.edit');
		$config=$model->getTemplateConfig($this->getName());
		$person=$model->getPerson();

		$this->project = $model->getProject();
		$this->overallconfig = $model->getOverallConfig();
		$this->config = $config;
		$this->person = $person;
		$this->showediticon = $isEditor;
		
		$ref=$model->getReferee();
		$this->referee = $ref;
		$this->history = $model->getHistory('ASC');
		if ($config['show_gameshistory'])
		{
			$this->games = $model->getGames();
			$this->teams = $model->getTeamsIndexedByPtid();
		}
		
		if ($person)
		{
			$extended = $this->getExtended($person->extended, 'referee');
			$this->extended = $extended;
		}
		
		$name = !empty($person) ? JoomleagueHelper::formatName(null, $person->firstname, $person->nickname,  $person->lastname,  $this->config["name_format"]) : "";
				
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_REFEREE_PAGE_TITLE'));
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
		$this->pagetitle = JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]);
		$document->setTitle($this->pagetitle);
		
		parent::display($tpl);
	}
}
