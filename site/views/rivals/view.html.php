<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class JoomleagueViewRivals extends JLGView
{
	function display( $tpl = null )
	{
		// Get a refrence of the page instance in joomla
		$document	= JFactory::getDocument();

		$model	= $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		
		$this->assignRef( 'project', $model->getProject() );
		$this->assignRef( 'overallconfig', $model->getOverallConfig() );
		if ( !isset( $this->overallconfig['seperator'] ) )
		{
			$this->overallconfig['seperator'] = "-";
		}
		$this->assignRef( 'config', $config );
		$this->assignRef( 'opos', $model->getOpponents() );
		$this->assignRef( 'team', $model->getTeam() );
		
		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_RIVALS_PAGE_TITLE'));
		if (!empty($this->team))
		{
			$titleInfo->team1Name = $this->team->name;
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