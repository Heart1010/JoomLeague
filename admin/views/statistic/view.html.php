<?php
/**
 * @copyright	Copyright (C) 2006-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.filesystem.file' );

require_once( JPATH_COMPONENT_ADMINISTRATOR . DS . 'statistics' . DS . 'base.php' );

/**
 * HTML View class for the Joomleague component
 *
 * @static
 * @package		Joomleague
 * @since 1.5
 */
class JoomleagueViewStatistic extends JLGView
{
	function display($tpl = null)
	{
		$this->form = $this->get('form');
		$this->edit = JRequest::getVar('edit',true);

		// icon
		//if there is no icon selected, use default icon
		$default = JoomleagueHelper::getDefaultPlaceholder("icon");
		$icon =	$this->form->getValue('icon');
		if (empty($icon))
		{
			$this->form->setValue('icon', $default);
		}
		$class = $this->form->getValue('class');
		if (!empty($class))
		{
			/*
			 * statistic class parameters
			 */
			$class = &JLGStatistic::getInstance($class);
			$this->assign( 'calculated',   $class->getCalculated());
		}

		$this->addToolbar();
		
		JHtml::_('behavior.tooltip');
		
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{		
		// Set toolbar items for the page
		$text = !$this->edit ? JText::_( 'COM_JOOMLEAGUE_GLOBAL_NEW' ) : JText::_( 'COM_JOOMLEAGUE_GLOBAL_EDIT' ).': '.JText::_($this->form->getValue('name'));
		JToolBarHelper::title(   JText::_( 'COM_JOOMLEAGUE_ADMIN_STAT_TITLE' ).': <small><small>[ ' . $text.' ]</small></small>', 'statistics.png' );
		if ( !$this->edit )
		{
			JLToolBarHelper::apply('statistic.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('statistic.cancel');
		}
		else
		{
			// for existing items the button is renamed `close` and the apply button is showed
			JLToolBarHelper::save('statistic.save');
			JLToolBarHelper::apply('statistic.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('statistic.cancel', 'COM_JOOMLEAGUE_GLOBAL_CLOSE' );
		}
		JToolBarHelper::help( 'screen.joomleague', true );	
	}
}