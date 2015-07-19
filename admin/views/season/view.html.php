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
class JoomleagueViewSeason extends JLGView
{

	public function display($tpl=null)
	{
		$this->form = $this->get('form');
		
		// @todo check!
		//$extended = $this->getExtended($season->extended, 'season');
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
		$edit = $this->input->get('edit',true);
		$text = !$edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT');

		JLToolBarHelper::save('season.save');

		if (!$edit)
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_SEASON_ADD_NEW'),'seasons');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('season.cancel');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_SEASON_EDIT'). ': ' . $this->form->getValue('name') ,'seasons');
			JLToolBarHelper::apply('season.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('season.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}		
}
