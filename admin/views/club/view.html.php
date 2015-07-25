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
class JoomleagueViewClub extends JLGView
{

	public function display($tpl=null)
	{
		$this->form = $this->get('form');	
		$this->edit = $this->input->get('edit',true);
		$extended = $this->getExtended($this->form->getValue('extended'), 'club');
		$this->extended = $extended;

		$this->addToolbar();
		parent::display($tpl);	
	}

	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{
		JLToolBarHelper::save('club.save');

		if (!$this->edit)
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_ADD_NEW'),'jl-clubs');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('club.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_EDIT'). ': ' . $this->form->getValue('name'), 'jl-clubs');
			JLToolBarHelper::apply('club.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('club.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		
		JToolBarHelper::help('screen.joomleague',true);		
	}	
}
