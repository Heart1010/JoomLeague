<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class JoomleagueViewProjectHeading extends JLGView
{
    function display( $tpl = null )
    {
        $model = $this->getModel();
        $overallconfig = $model->getOverallConfig();
        $project = $model->getProject();
        $this->project = $project;
        $division = $model->getDivision(JRequest::getInt('division', 0));
		$this->division = $division;
        $this->overallconfig = $overallconfig;
        parent::display($tpl);
    }
}
