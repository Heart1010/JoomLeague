<?php defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT . DS . 'helpers' . DS . 'pagination.php' );

jimport( 'joomla.application.component.view' );

class JoomleagueViewReferees extends JLGView
{

	function display( $tpl = null )
	{
		// Get a refrence of the page instance in joomla
		$document	= JFactory::getDocument();

		$model	= $this->getModel();
		$config = $model->getTemplateConfig($this->getName());
		
		if ( !$config )
		{
			$config	= $model->getTemplateConfig( 'players' );
		}

		$this->assignRef( 'project', $model->getProject() );
		$this->assignRef( 'overallconfig', $model->getOverallConfig() );
		$this->assignRef( 'config', $config );

		$this->assignRef( 'rows', $model->getReferees() );
//		$this->assignRef( 'positioneventtypes', $model->getPositionEventTypes( ) );

		// Set page title
		$titleInfo = JoomleagueHelper::createTitleInfo(JText::_('COM_JOOMLEAGUE_REFEREES_PAGE_TITLE'));
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
		
		parent::display( $tpl );
	}

}
?>