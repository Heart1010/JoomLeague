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
class JoomleagueViewLeague extends JLGView
{
	public function display($tpl=null)
	{
		$this->form = $this->get('form');
		$extended = $this->getExtended($this->form->getValue('extended'), 'league');
		$this->extended = $extended;

		$this->addToolbar();			
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar
	*/
	protected function addToolbar()
	{	
		// Set toolbar items for the page
		$edit = $this->input->get('edit',true);
		$text = !$edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT');

		JLToolBarHelper::save('league.save');

		if (!$edit)
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_LEAGUE_ADD_NEW'),'leagues');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('league.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_LEAGUE_EDIT'). ': ' . $this->form->getValue('name'),'leagues');
			JLToolBarHelper::apply('league.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('league.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}	
}
