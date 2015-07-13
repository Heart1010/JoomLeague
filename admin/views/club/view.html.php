<?php
/**
 * @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * HTML View class for the Joomleague component
 *
 * @static
 * @package	JoomLeague
 * @since	0.1
 */
class JoomleagueViewClub extends JLGView
{

	function display($tpl=null)
	{
		$this->form = $this->get('form');	
		$this->edit = JRequest::getVar('edit',true);
		$extended = $this->getExtended($this->form->getValue('extended'), 'club');
		$this->extended = $extended;

		$this->addToolbar();
		parent::display($tpl);	
	}

	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{
		JLToolBarHelper::save('club.save');

		if (!$this->edit)
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_ADD_NEW'),'clubs');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('club.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_CLUB_EDIT'). ': ' . $this->form->getValue('name'), 'clubs');
			JLToolBarHelper::apply('club.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('club.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		
		JToolBarHelper::help('screen.joomleague',true);		
	}	
}
?>
