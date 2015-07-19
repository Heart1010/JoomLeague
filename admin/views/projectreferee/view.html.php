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
class JoomleagueViewProjectReferee extends JLGView
{

	public function display($tpl=null)
	{
		$app		= JFactory::getApplication();
		$db	 		= JFactory::getDbo();
		$uri		= JFactory::getURI();
		$user		= JFactory::getUser();
		$model		= $this->getModel();

		$lists=array();
		//get the projectreferee data of the project_team
		$projectreferee = $this->get('data');
		$isNew=($projectreferee->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg=JText::sprintf('DESCBEINGEDITTED',JText::_('COM_JOOMLEAGUE_ADMIN_P_REF_THE_PREF'),$projectreferee->name);
			$app->redirect('index.php?option=com_joomleague',$msg);
		}

		// Edit or Create?
		if ($isNew)
		{
			$projectreferee->order=0;
		}

		//build the html select list for positions
		$refereepositions=array();
		$refereepositions[]=JHtml::_('select.option',	'0',JText::_('COM_JOOMLEAGUE_GLOBAL_SELECT_REF_POS'));
		if ($res= $model->getRefereePositions())
		{
			$refereepositions=array_merge($refereepositions,$res);
		}
		$lists['refereepositions']=JHtml::_(	'select.genericlist',
												$refereepositions,
												'project_position_id',
												'class="inputbox" size="1"',
												'value',
												'text',$projectreferee->project_position_id);
		unset($refereepositions);
                
		$projectws	= $this->get('Data','project');

		$this->form = $this->get('form');			
		$this->projectws = $projectws;
		$this->lists = $lists;
		$this->projectreferee = $projectreferee;
		$extended = $this->getExtended($projectreferee->extended, 'projectreferee');		
		$this->extended = $extended;
		
		$this->addToolbar();		
		parent::display($tpl);
	}

	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('Edit project depending referee data'),'Referees');

		// Set toolbar items for the page
		$edit=JRequest::getVar('edit',true);
		$text=!$edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT');
		JLToolBarHelper::save('projectreferee.save');

		if (!$edit)
		{
			JLToolBarHelper::cancel('projectreferee.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JLToolBarHelper::apply('projectreferee.apply');
			JLToolBarHelper::cancel('projectreferee.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::back();
		JToolBarHelper::help('screen.joomleague',true);
	}	
	
}
