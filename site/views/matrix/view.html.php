<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class JoomleagueViewMatrix extends JLGView
{
	function display( $tpl = null )
	{
		// Get a refrence of the page instance in joomla
		$document= JFactory::getDocument();

		$model = $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		$project =& $model->getProject();
		
		$this->assignRef( 'model', $model);
		$this->assignRef( 'project', $project);
		$this->assignRef( 'overallconfig', $model->getOverallConfig() );

		$this->assignRef( 'config', $config );

		$this->assignRef( 'divisionid', $model->getDivisionID() );
		$this->assignRef( 'roundid', $model->getRoundID() );
		$this->assignRef( 'division', $model->getDivision() );
		$this->assignRef( 'round', $model->getRound() );
		$this->assignRef( 'teams', $model->getTeamsIndexedByPtid( $model->getDivisionID() ) );
		$this->assignRef( 'results', $model->getMatrixResults( $model->projectid ) );
		
		if ($project->project_type == 'DIVISIONS_LEAGUE' && !$this->divisionid )
		{
			$divisions = $model->getDivisions();
			$this->assignRef('divisions', $divisions);
		}
		
		if(!is_null($project)) {
			$this->assignRef( 'favteams', $model->getFavTeams() );
		}
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_MATRIX_PAGE_TITLE'));
		if (!empty($this->round))
		{
			$titleInfo->roundName = $this->round->name;
		}
		if (!empty($this->project))
		{
			$titleInfo->projectName = $this->project->name;
			$titleInfo->leagueName = $this->project->league_name;
			$titleInfo->seasonName = $this->project->season_name;
		}
		if (!empty( $this->division ) && $this->division->id != 0)
		{
			$titleInfo->divisionName = $this->division->name;
		}
		$this->assignRef('pagetitle', JoomleagueHelper::formatTitle($titleInfo, $this->config["page_title_format"]));
		$document->setTitle($this->pagetitle);
		
		parent::display( $tpl );
	}
}
?>