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
class JoomleagueViewEventtype extends JLGView
{
	public function display($tpl=null)
	{
		$this->form = $this->get('form');		
		//$extended = $this->getExtended($projectreferee->extended, 'eventtype');
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
		$text = !$edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT'). ': ' . JText::_($this->form->getValue('name'));
		JToolBarHelper::title((JText::_('COM_JOOMLEAGUE_ADMIN_EVENTTYPE_EVENT').': <small><small>[ '.$text.' ]</small></small>'),'jl-events');
		JLToolBarHelper::save('eventtype.save');
		if (!$edit)
		{
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('eventtype.cancel');
		}
		else
		{		
			JLToolBarHelper::apply('eventtype.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('eventtype.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::help('screen.joomleague',true);
	}
}
