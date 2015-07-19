<?php
/**
 * Joomleague
 *
 * @copyright	Copyright (C) 2006-2015 joomleague.at. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @link		http://www.joomleague.at
 */
defined('_JEXEC') or die;


require_once JPATH_COMPONENT_ADMINISTRATOR.'/statistics/base.php';

/**
 * HTML View class
 */
class JoomleagueViewStatistic extends JLGView
{
	public function display($tpl = null)
	{
		$this->form = $this->get('form');
		$this->edit = $this->input->get('edit',true);

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
			$class = JLGStatistic::getInstance($class);
			$this->calculated = $class->getCalculated();
		}

		$this->addToolbar();
		
		JHtml::_('behavior.tooltip');
		
		parent::display($tpl);
	}
	
	/**
	* Add the page title and toolbar.
	*/
	protected function addToolbar()
	{		
		// Set toolbar items for the page
		$text = !$this->edit ? JText::_('COM_JOOMLEAGUE_GLOBAL_NEW') : JText::_('COM_JOOMLEAGUE_GLOBAL_EDIT').': '.JText::_($this->form->getValue('name'));
		JToolBarHelper::title(JText::_('COM_JOOMLEAGUE_ADMIN_STAT_TITLE').': <small><small>[ ' . $text.' ]</small></small>', 'jl-statistics' );
		if (!$this->edit)
		{
			JLToolBarHelper::apply('statistic.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('statistic.cancel');
		}
		else
		{
			JLToolBarHelper::save('statistic.save');
			JLToolBarHelper::apply('statistic.apply');
			JToolBarHelper::divider();
			JLToolBarHelper::cancel('statistic.cancel', 'COM_JOOMLEAGUE_GLOBAL_CLOSE' );
		}
		JToolBarHelper::help( 'screen.joomleague', true );	
	}
}
