<?php
/**
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license		GNU/GPL,see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License,and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Joomleague component
 *
 * @static
 * @package	JoomLeague
 */
class JoomleagueViewSeason extends JLGView
{

	function display($tpl=null)
	{
		$this->form = $this->get('form');
		
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
		$edit=JRequest::getVar('edit',true);
		$text=!$edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT');

		JLToolBarHelper::save('season.save');

		if (!$edit)
		{
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_SEASON_ADD_NEW'),'seasons');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('season.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_SEASON_EDIT'). ': ' . $this->form->getValue('name') ,'seasons');
			JLToolBarHelper::apply('season.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('season.cancel','COM_JOOMLEAGUE_GLOBAL_CLOSE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.joomleague',true);
	}		
}
