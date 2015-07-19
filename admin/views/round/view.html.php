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

class JoomleagueViewRound extends JLGView
{
	public function display($tpl=null)
	{
		if ($this->getLayout() == 'form')
		{
			$this->_displayForm($tpl);
			return;
		}
		parent::display($tpl);
	}

	function _displayForm($tpl)
	{
		$option = $this->input->getCmd('option');
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
		$model = $this->getModel();
		$lists=array();

		//get the matchday
		$round = $this->get('data');
		$isNew=($round->id < 1);

		// fail if checked out not by 'me'
		if ($model->isCheckedOut($user->get('id')))
		{
			$msg=JText::sprintf('DESCBEINGEDITTED',JText::_('The matchday'),$round->name);
			$app->redirect('index.php?option='.$option,$msg);
		}

		// Edit or Create?
		if (!$isNew)
		{
			$model->checkout($user->get('id'));
		}
		else
		{
			// initialise new record
			$round->order=0;
		}

		$projectws = $this->get('Data','project');
		$this->projectws = $projectws;
		 #$this->lists = $lists;
		$this->matchday = $round;

		$this->form = $this->get('form');	
		//$extended = $this->getExtended($round->extended, 'round');
		//$this->extended = $extended;		
		$this->addToolbar();		
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{ 
		// Set toolbar items for the page
		$edit = $this->input->get('edit', true);
		$text = !$edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT');
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_ROUND_TITLE'). ': ' . $this->matchday->name,'clubs','Matchdays');

		JLToolBarHelper::save('round.save');
		JLToolBarHelper::apply('round.apply');
		if (!$edit)
		{
			JLToolBarHelper::cancel('round.cancel');
		}
		else
		{
			JLToolBarHelper::cancel('round.cancel', 'COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::help('screen.joomleague', true);	
	}
}
