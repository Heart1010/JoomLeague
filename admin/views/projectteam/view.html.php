<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


/**
 * HTML View class
 */
class JoomleagueViewProjectteam extends JLGView
{
	public function display($tpl = null)
	{
		$option 	= JRequest::getCmd('option');
		$app		= JFactory::getApplication();
		$project_id = $app->getUserState( $option . 'project' );
		$uri 		= JFactory::getURI();
		$user 		= JFactory::getUser();
		$model		= $this->getModel();
		$lists		= array();

		//get the project_team
		$project_team = $this->get('data');
		$isNew = ($project_team->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg = JText::sprintf('DESCBEINGEDITTED',JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_THE_TEAM'),$project_team->name);
			$app->redirect('index.php?option=com_joomleague',$msg);
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout($user->get('id'));
		}
		else
		{
			// initialise new record
			$project_team->order = 0;
			// $project_team->parent_id = 0;
		}
		$projectws	= $this->get('Data','project');

		//build the html select list for days of week
		if ($trainingData=$model->getTrainigData($project_team->id))
		{
			$daysOfWeek=array(	0 => JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT'),
			1 => JText::_('COM_JOOMLEAGUE_GLOBAL_MONDAY'),
			2 => JText::_('COM_JOOMLEAGUE_GLOBAL_TUESDAY'),
			3 => JText::_('COM_JOOMLEAGUE_GLOBAL_WEDNESDAY'),
			4 => JText::_('COM_JOOMLEAGUE_GLOBAL_THURSDAY'),
			5 => JText::_('COM_JOOMLEAGUE_GLOBAL_FRIDAY'),
			6 => JText::_('COM_JOOMLEAGUE_GLOBAL_SATURDAY'),
			7 => JText::_('COM_JOOMLEAGUE_GLOBAL_SUNDAY'));
			$dwOptions=array();
			foreach($daysOfWeek AS $key => $value){$dwOptions[]=JHtml::_('select.option',$key,$value);}
			foreach ($trainingData AS $td)
			{
				$lists['dayOfWeek'][$td->id]=JHtml::_('select.genericlist',$dwOptions,'dw_'.$td->id,'class="inputbox"','value','text',$td->dayofweek);
			}
			unset($daysOfWeek);
			unset($dwOptions);
		}

		if ($projectws->project_type == 'DIVISIONS_LEAGUE') // No divisions
		{
			//build the html options for divisions
			$division[]=JHtml::_('select.option', '0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_DIVISION'));
			$mdlDivisions = JModelLegacy::getInstance("divisions", "JoomLeagueModel");
			if ($res = $mdlDivisions->getDivisions($project_id)){
				$division=array_merge($division,$res);
			}
			$lists['divisions']=$division;
				
			unset($res);
			unset($divisions);
		}

		$this->form = $this->get('form');	
		$extended = $this->getExtended($project_team->extended, 'projectteam');
		$this->extended = $extended;
		//$this->imageselect = $imageselect;
		$this->projectws = $projectws;
		$this->lists = $lists;
		$this->project_team = $project_team;
		$this->trainingData = $trainingData;
		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_P_TEAM_TITLE') . ': '. $this->project_team->name);
		
		JLToolBarHelper::save('projectteam.save');
		JLToolBarHelper::apply('projectteam.apply');
		JLToolBarHelper::cancel('projectteam.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		JToolBarHelper::divider();
		JToolBarHelper::back();
		JToolBarHelper::help('screen.joomleague',true);
	}
}
