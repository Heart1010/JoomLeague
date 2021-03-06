<?php
/**
 * @copyright	Copyright (C) 2007-2014 joomleague.at. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

class JFormFieldPageTitleFormat extends JFormField
{
	protected $type = 'pagetitleformat';

	function getInput() {
		$lang = JFactory::getLanguage();
		$extension = "com_joomleague";
		$source = JPath::clean(JPATH_ADMINISTRATOR . '/components/' . $extension);
		$lang->load($extension, JPATH_ADMINISTRATOR, null, false, false)
		||	$lang->load($extension, $source, null, false, false)
		||	$lang->load($extension, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
		||	$lang->load($extension, $source, $lang->getDefault(), false, false);
		
		$mitems = array();
		$mitems[] = JHtml::_('select.option', 0, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_PROJECT'));
		$mitems[] = JHtml::_('select.option', 1, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_PROJECT_LEAGUE'));
		$mitems[] = JHtml::_('select.option', 2, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_PROJECT_LEAGUE_SEASON'));
		$mitems[] = JHtml::_('select.option', 3, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_PROJECT_SEASON'));
		$mitems[] = JHtml::_('select.option', 4, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_LEAGUE'));
		$mitems[] = JHtml::_('select.option', 5, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_LEAGUE_SEASON'));
		$mitems[] = JHtml::_('select.option', 6, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_SEASON'));
		$mitems[] = JHtml::_('select.option', 7, JText::_('COM_JOOMLEAGUE_FES_PARAM_PAGE_TITLE_NONE'));
		
		$output= JHtml::_('select.genericlist',  $mitems,
							$this->name,
							'class="inputbox" size="1"', 
							'value', 'text', $this->value, $this->id);
		return $output;
	}
}
