<?php defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class JoomleagueViewBackButton extends JLGView
{
	function display( $tpl = null )
	{
		//$model = $this->getModel();

		//$this->project = $model->getProject();
		//$this->overallconfig = $model->getOverallConfig();

		parent::display( $tpl );
	}
}
