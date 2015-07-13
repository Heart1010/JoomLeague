<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class JoomleagueViewStatsRanking extends JLGView
{
	function display($tpl = null)
	{
		// Get a refrence of the page instance in joomla
		$document = JFactory::getDocument();

		// read the config-data from template file
		$model = $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		
		$this->assignRef( 'project', $model->getProject() );
		$this->assignRef( 'division', $model->getDivision() );
		$this->assignRef( 'teamid', $model->getTeamId() );
		$teams = $model->getTeamsIndexedById();
		if ( $this->teamid != 0 )
		{
			foreach ( $teams AS $k => $v)
			{
				if ($k != $this->teamid)
				{
					unset( $teams[$k] );
				}
			}
		}

		$this->assignRef( 'teams', $teams );
		$this->assignRef( 'overallconfig', $model->getOverallConfig() );
		$this->assignRef( 'config', $config );
		$this->assignRef( 'favteams', $model->getFavTeams() );
		$this->assignRef( 'stats', $model->getProjectUniqueStats() );
		$this->assignRef( 'playersstats', $model->getPlayersStats() );
		$this->assignRef( 'limit', $model->getLimit() );
		$this->assignRef( 'limitstart', $model->getLimitStart() );
		$this->assign( 'multiple_stats', count($this->stats) > 1 );

		$prefix = JText::_('COM_JOOMLEAGUE_STATSRANKING_PAGE_TITLE');
		if ( $this->multiple_stats )
		{
			$prefix .= " - " . JText::_( 'COM_JOOMLEAGUE_STATSRANKING_TITLE' );
		}
		else
		{
			// Next query will result in an array with exactly 1 statistic id
			$sid = array_keys($this->stats);
			// Take the first result then.
			$prefix .= " - " . $this->stats[$sid[0]]->name;
		}

		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo($prefix);
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
